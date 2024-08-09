<?php

namespace App\Http\Controllers;

use App\Delivery;
use Carbon\Carbon;
use Session;
use DB;
use CRUDbooster;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
//\crocodicstudio\crudbooster\controllers\CBController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //if(!CRUDBooster::isView()) CRUDBooster::redirect(CRUDBooster::adminPath(),trans('crudbooster.denied_access'));
   
        //Create your own query 
        $data = [];
        $data['page_title'] = 'WSH Delivery Details';
        $data['result'] = DB::connection('oracle')->table('wsh_delivery_details')
        ->join('wsh_delivery_assignments','wsh_delivery_details.delivery_detail_id','=','wsh_delivery_assignments.delivery_detail_id')
        ->join('hz_cust_accounts','wsh_delivery_details.customer_id','=','hz_cust_accounts.cust_account_id')
        ->join('hz_parties','hz_cust_accounts.party_id','=','hz_parties.party_id')
        ->join('mtl_system_items_b','wsh_delivery_details.inventory_item_id','=','mtl_system_items_b.inventory_item_id')
        ->select(
        'wsh_delivery_assignments.delivery_id',
        'wsh_delivery_details.delivery_detail_id',
        'wsh_delivery_details.requested_quantity',
        'wsh_delivery_details.picked_quantity',
        'wsh_delivery_details.shipped_quantity',
        'wsh_delivery_details.released_status',
        'wsh_delivery_details.cust_po_number as customer_po',
        'hz_parties.party_name AS customer_name',
        'mtl_system_items_b.segment1 AS digits_code',
        'mtl_system_items_b.description AS item_description')
        //->distinct()
        ->where('mtl_system_items_b.organization_id',224)
        //->where('wsh_delivery_details.released_status','C')
        ->orderby('wsh_delivery_assignments.creation_date','desc')
        ->paginate(5000);
        //->get();

        /*
        B: Backordered- Line failed to be allocated in Inventory 
        C: Shipped -Line has been shipped 
        D: Cancelled -Line is Cancelled 
        N: Not Ready for Release -Line is not ready to be released 
        R: Ready to Release: Line is ready to be released 
        S: Released to Warehouse: Line has been released to Inventory for processing 
        X: Not Applicable- Line is not applicable for Pick Release 
        Y: Staged- Line has been picked and staged by Inventory
        */

        //Create a view. Please use `cbView` method instead of view method from laravel.
        return view('delivery.index',$data)->render();
    }

    public function getPendingDelivery(){
        $deliveries = [];

        $validator = Validator::make([
            'datefrom' => request('datefrom'),
            'dateto' => request('dateto'),
        ],[
            'datefrom' => 'required',
            'dateto' => 'required'
        ]);

        if($validator->fails()){
            $result['api_status']  = 0;
            $result['api_message'] = 'error';
            $result['data'] = [
                'Invalid date range. Please enter a valid date range in Y-m-d H:i:s format!'
            ];
            return response()->json( $result, 200 );
        }

        $datefrom = Carbon::parse(request('datefrom'))->format('Y-m-d H:i:s');
        $dateto = Carbon::parse(request('dateto'))->format('Y-m-d H:i:s');

        //get digitswarehouse code

        $deliveriesQty = DB::table('ebs_pull')
            ->leftJoin('items','ebs_pull.ordered_item','=','items.digits_code')
            ->where('ebs_pull.status','RECEIVED') //change the status to RECEIVED (ready for push)
            ->whereBetween('ebs_pull.received_at',[$datefrom, $dateto])
            ->select(
                'ebs_pull.dr_number',
                DB::raw('SUM(ebs_pull.shipped_quantity) as total_qty'),
                DB::raw('SUM(ebs_pull.shipped_quantity*items.current_srp) as total_amount')
            )->take(100)
            ->groupBy('ebs_pull.dr_number')
            ->get()->toArray();
        
        $arraySumQty = [];
        $arraySumAmount = [];
       
        foreach ($deliveriesQty as $item) {
            $arraySumQty[$item->dr_number] = $item->total_qty;
            $arraySumAmount[$item->dr_number] = $item->total_amount;
        }
        
        $deliveryItems = DB::table('ebs_pull')
            ->leftJoin('serials','ebs_pull.id','=','serials.ebs_pull_id')
            ->leftJoin('items','ebs_pull.ordered_item','=','items.digits_code')
            ->leftJoin('stores', function($join) {
                $join->on('ebs_pull.customer_name', '=', 'stores.bea_so_store_name')
                ->orOn('ebs_pull.customer_name', '=', 'stores.bea_mo_store_name');
            })
            ->where('ebs_pull.status','RECEIVED') //change the status to RECEIVED (ready for push)
            ->whereBetween('ebs_pull.received_at',[$datefrom, $dateto])
            ->select(
                'ebs_pull.*',
                'serials.serial_number',
                'items.digits_code',
                'items.item_description',
                'items.current_srp', 
                'items.has_serial',               
                // 'stores.warehouse_code',
                'stores.pos_warehouse_name'
            )->take(100)->get();

        foreach ($deliveryItems ?? [] as $item) {
            if (!isset($deliveries[$item->dr_number])) {
                $deliveries[$item->dr_number] = [
                    'reference_code' => $item->dr_number,
                    'transaction_date' => Carbon::parse($item->received_at)->format('Y-m-d'),
                    'from_warehouse' => 'DIGITS WAREHOUSE', //digitswarehouse
                    'destination_store' => $item->pos_warehouse_name,
                    'total_qty' => $arraySumQty[$item->dr_number],
                    'total_amount' => $arraySumAmount[$item->dr_number],
                    'memo' => $item->customer_po,
                    'created_date' => $item->data_pull_date,
                    'delivery_lines' => []
                ];
            }

            $deliveries[$item->dr_number]['delivery_lines'][] = [
                'line_number' => $item->line_number,
                'digits_code' => $item->ordered_item,
                'item_description' => $item->item_description,
                'price' => $item->current_srp,
                'uom' => 'PCS',
                'qty' => (!is_null($item->serial_number)) ? 1 : $item->shipped_quantity,
                'has_serial' => $item->has_serial,
                'serial_number' => $item->serial_number,
                'created_date' => $item->data_pull_date,
                'transaction_date' => Carbon::parse($item->received_at)->format('Y-m-d')
            ];
        }
        
        $result['api_status']  = 1;
		$result['api_message'] = 'success';
        $result['data'] = array_values($deliveries);
        
        return response()->json( $result, 200 );
    }
}

<?php

namespace App\Http\Controllers;

use App\Delivery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use DB;
use CRUDbooster;

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
        // $datefrom = '2023-06-02';
        // $dateto = '2023-06-02';
        $datefrom = Carbon::today()->format('Y-m-d');
        $dateto = Carbon::today()->subDays(1)->format('Y-m-d');

        //get digitswarehouse code

        $deliveryItems = DB::table('ebs_pull')
            ->leftJoin('serials','ebs_pull.id','=','serials.ebs_pull_id')
            ->leftJoin('stores', function($join) {
                $join->on('ebs_pull.customer_name', '=', 'stores.bea_so_store_name')
                ->orOn('ebs_pull.customer_name', '=', 'stores.bea_mo_store_name');
            })
            ->where('ebs_pull.status','RECEIVED') //change the status to RECEIVED (ready for push)
            ->whereBetween('ebs_pull.data_pull_date',[$datefrom, $dateto])
            ->select(
                'ebs_pull.*',
                'serials.serial_number',
                // 'stores.warehouse_code',
                'stores.pos_warehouse_name'
            )->get();

        foreach ($deliveryItems ?? [] as $item) {
            if (!isset($deliveries[$item->dr_number])) {
                $deliveries[$item->dr_number] = [
                    'reference_code' => $item->dr_number,
                    'transaction_date' => $item->data_pull_date,
                    'from_warehouse' => '', //digitswarehouse
                    'destination_store' => $item->pos_warehouse_name,
                    'memo' => $item->customer_po,
                    'created_date' => $item->data_pull_date,
                    'delivery_lines' => []
                ];
            }

            $deliveries[$item->dr_number]['delivery_lines'][] = [
                'digits_code' => $item->ordered_item,
                'qty' => $item->shipped_quantity,
                'serial_number' => $item->serial_number
            ];
        }
        
        return (json_encode(array_values($deliveries)));
    }
}

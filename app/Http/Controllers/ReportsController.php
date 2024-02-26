<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use DB;
use Session;
use CRUDbooster;
use PDO;
use Carbon\Carbon;
// use Excel;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends \crocodicstudio\crudbooster\controllers\CBController
{
    public function cbInit() {
    
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->cbLoader();
        $data = array();
        $data['page_title'] = 'Reports';
        $this->cbView("reports.index",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function generatePurchaseOrderReport($date_from, $date_to)
    {
        $datefrom = date("Y-m-d 00:00:00", strtotime($date_from));
        $dateto = date("Y-m-d 23:59:59", strtotime($date_to));
        
        // if (file_exists(public_path('export') .'/Export_PO_Report_'.date("Ymd", strtotime($date_from)).'_'.date("Ymd", strtotime($date_to)).'.xlsx')) {
        //     return [
        //         'success' => true,
        //         'path' =>  asset('export').'/Export_PO_Report_'.date("Ymd", strtotime($date_from)).'_'.date("Ymd", strtotime($date_to)).'.xlsx'
        //     ];
        // } 
        ini_set('max_execution_time', 0);
        
        $poDetails = DB::connection('oracle')->table('PO_HEADERS_ALL')
        ->leftJoin('PO_LINES_ALL','PO_HEADERS_ALL.PO_HEADER_ID','=','PO_LINES_ALL.PO_HEADER_ID')
        ->leftJoin('HR_LOCATIONS','PO_HEADERS_ALL.SHIP_TO_LOCATION_ID','=','HR_LOCATIONS.LOCATION_ID')
        ->leftJoin('HR_ALL_ORGANIZATION_UNITS','HR_LOCATIONS.INVENTORY_ORGANIZATION_ID','=','HR_ALL_ORGANIZATION_UNITS.ORGANIZATION_ID')
        ->leftJoin('PO_LINE_LOCATIONS_ALL','PO_LINES_ALL.PO_LINE_ID','=','PO_LINE_LOCATIONS_ALL.PO_LINE_ID')
        ->leftJoin('PO_VENDORS', 'PO_HEADERS_ALL.VENDOR_ID','=','PO_VENDORS.VENDOR_ID')
        ->leftJoin('MTL_SYSTEM_ITEMS', 'PO_LINES_ALL.ITEM_ID','=','MTL_SYSTEM_ITEMS.INVENTORY_ITEM_ID')
        ->where('PO_LINE_LOCATIONS_ALL.SHIP_TO_ORGANIZATION_ID', 224)
        ->where('MTL_SYSTEM_ITEMS.ORGANIZATION_ID', 223)
        ->whereBetween('PO_HEADERS_ALL.CREATION_DATE',[$datefrom, $dateto])
        ->select('PO_HEADERS_ALL.SEGMENT1 as PO_NUMBER',																																											
            'PO_HEADERS_ALL.CREATION_DATE',																																												
            'PO_HEADERS_ALL.COMMENTS as ORDER_TYPE',																																													
            'PO_HEADERS_ALL.REVISION_NUM as REV',																																												
            'PO_HEADERS_ALL.TYPE_LOOKUP_CODE as TYPE',
            DB::raw("COALESCE (PO_HEADERS_ALL.AUTHORIZATION_STATUS, 'INCOMPLETE') as APPROVAL_STATUS"),																																											
            'PO_HEADERS_ALL.CREATION_DATE as ORDER_DATE',																																												
            // 'PO_HEADERS_ALL.VENDOR_ID',	
            'PO_VENDORS.VENDOR_NAME',																																											
            'PO_HEADERS_ALL.VENDOR_SITE_ID',																																												
            'PO_HEADERS_ALL.CURRENCY_CODE as CURRENCY',																																												
            DB::raw("SUM ((PO_LINES_ALL.UNIT_PRICE * PO_LINES_ALL.QUANTITY)) as AMOUNT"),
            DB::raw("SUM (PO_LINE_LOCATIONS_ALL.QUANTITY) as PO_QUANTITY"),
            DB::raw("SUM (PO_LINE_LOCATIONS_ALL.QUANTITY_RECEIVED) as QUANTITY_RECEIVED"),
            DB::raw("SUM (PO_LINE_LOCATIONS_ALL.QUANTITY_CANCELLED) as QUANTITY_CANCELLED"),
            DB::raw("SUM (PO_LINE_LOCATIONS_ALL.QUANTITY_BILLED) as QUANTITY_BILLED"),
            // DB::raw("COALESCE (FND_USER.DESCRIPTION, FND_USER.USER_NAME) as BUYER"),																		
            'PO_HEADERS_ALL.CREATED_BY',																																												
            'PO_HEADERS_ALL.CLOSED_CODE',																																											
            // 'PO_LINES_ALL.ITEM_ID',	
            'MTL_SYSTEM_ITEMS.SEGMENT1 as ITEM_CODE',
            'PO_LINES_ALL.ITEM_DESCRIPTION',																																												
            'PO_LINES_ALL.LINE_NUM',																																												
            'PO_LINES_ALL.QUANTITY',																																												
            'PO_LINES_ALL.UNIT_MEAS_LOOKUP_CODE',																																												
            'PO_LINES_ALL.UNIT_PRICE',																																												
            'PO_LINES_ALL.CLOSED_CODE as LINE_CLOSED_CODE',																																												
            'PO_LINES_ALL.CANCEL_FLAG',																																												
            'PO_LINES_ALL.LINE_TYPE_ID',																																												
            'PO_LINES_ALL.PO_LINE_ID',
            'PO_LINE_LOCATIONS_ALL.NEED_BY_DATE',																																												
            'HR_ALL_ORGANIZATION_UNITS.NAME',																																												
            'PO_LINES_ALL.CANCEL_REASON',																																												
            'PO_LINES_ALL.CLOSED_REASON')
        ->groupBy('PO_HEADERS_ALL.SEGMENT1',																																												
            'PO_HEADERS_ALL.COMMENTS',																																												
            'PO_HEADERS_ALL.REVISION_NUM',																																												
            'PO_HEADERS_ALL.TYPE_LOOKUP_CODE',																																												
            'PO_HEADERS_ALL.AUTHORIZATION_STATUS',																																												
            'PO_HEADERS_ALL.CREATION_DATE',																																												
            // 'PO_HEADERS_ALL.VENDOR_ID',
            'PO_VENDORS.VENDOR_NAME',																																												
            'PO_HEADERS_ALL.VENDOR_SITE_ID',																																												
            'PO_HEADERS_ALL.CURRENCY_CODE',																																						
            'PO_HEADERS_ALL.CREATED_BY',																																												
            'PO_HEADERS_ALL.CLOSED_CODE',																																												
            // 'PO_LINES_ALL.ITEM_ID',
            'MTL_SYSTEM_ITEMS.SEGMENT1',
            'PO_LINES_ALL.ITEM_DESCRIPTION',																																												
            'PO_LINES_ALL.LINE_NUM',																																												
            'PO_LINES_ALL.QUANTITY',																																												
            'PO_LINES_ALL.UNIT_MEAS_LOOKUP_CODE',																																												
            'PO_LINES_ALL.UNIT_PRICE',																																												
            'PO_LINES_ALL.CLOSED_CODE',																																												
            'PO_LINES_ALL.CANCEL_FLAG',																																												
            'PO_LINES_ALL.LINE_TYPE_ID',																																												
            'PO_LINES_ALL.PO_LINE_ID',	
            'PO_LINE_LOCATIONS_ALL.NEED_BY_DATE',																																												
            'HR_ALL_ORGANIZATION_UNITS.NAME',																																												
            'PO_LINES_ALL.CANCEL_REASON',																																												
            'PO_LINES_ALL.CLOSED_REASON')
        ->orderBy('PO_HEADERS_ALL.SEGMENT1','ASC')
        ->get();

        $po_details = array();

        foreach($poDetails as $key => $value){
            $receipt_status = 'OPEN';
            $closed_qty = 0;
            
            $over_qty = ($value->quantity_received + intval($value->quantity_cancelled)) - $value->po_quantity;
            
            $poBuyer = DB::connection('oracle')->table('FND_USER')
                ->where('FND_USER.USER_ID', $value->created_by)
                ->select(DB::raw("COALESCE (FND_USER.DESCRIPTION, FND_USER.USER_NAME) as BUYER"))
                ->first();
            
            if($value->line_closed_code == 'CLOSED'){
                $closed_qty = $value->po_quantity - ($value->quantity_received + intval($value->quantity_cancelled));
                if($closed_qty < 0){
                    $closed_qty = 0;
                }
            }

            $incoming_qty = $value->po_quantity - ($value->quantity_received + intval($value->quantity_cancelled) + $closed_qty);
            $actual_po_qty = $value->po_quantity - (intval($value->quantity_cancelled) + $closed_qty);

            if($value->po_quantity == $value->quantity_received){
                $receipt_status = 'CLOSED';
            }
            else if($value->po_quantity == ($value->quantity_received + intval($value->quantity_cancelled) + $closed_qty) ){
                $receipt_status = 'CLOSED';
            }
            else if($value->po_quantity < ($value->quantity_received + intval($value->quantity_cancelled)) ){
                $receipt_status = 'CLOSED-OVER';
            }

            $itemDetails =  DB::connection('imfs')->table('item_masters')
                ->leftJoin('brands','item_masters.brands_id','=','brands.id')
                ->where('digits_code', $value->item_code)
                ->select(
                    'item_masters.digits_code',
                    'item_masters.item_description',
                    'brands.brand_description',
                    'item_masters.current_srp',
                    'item_masters.promo_srp',
                    'item_masters.landed_cost',
                    'item_masters.working_landed_cost')
                ->first();

            array_push($po_details,
            [
                'YEAR' => date('Y', strtotime($value->creation_date)),
                'PO_NUMBER' => $value->po_number,
                'DIGITS_CODE' => $itemDetails->digits_code,
                'IMFS_DESCRIPTION' => $itemDetails->item_description,// preg_replace('/[\s]+/', ' ', $value->item_description),
                'APPROVAL_STATUS' => $value->approval_status,
                'HEADER_CLOSURE_STATUS' => ($value->closed_code == 'CLOSED') ? $value->closed_code : 'OPEN',
                'LINE_CLOSURE_STATUS' => ($value->line_closed_code == 'CLOSED') ? $value->line_closed_code : 'OPEN',
                'PO_RECEIPT_STATUS' => $receipt_status,
                'ORDER_DATE' => date('Y-m-d', strtotime($value->order_date, "+5 day")),
                'NEED_BY_DATE' => date('Y-m-d', strtotime($value->need_by_date)),
                'BUYER' => $poBuyer->buyer,
                'PO_QUANTITY' => $value->po_quantity,
                'INCOMING_QUANTITY' => ($incoming_qty < 0) ? 0 : $incoming_qty,
                'RECEIVED_QUANTITY' => $value->quantity_received,
                'CANCELLED_QUANTITY' => intval($value->quantity_cancelled) + $closed_qty,
                'OVER_QUANTITY' => ($over_qty < 0) ? 0 : $over_qty,
                'INVOICE_QUANTITY' => $value->quantity_billed,
                'CANCEL_REASON' => ($value->cancel_flag == 'Y') ? strtoupper($value->cancel_reason) : (($value->closed_reason == 'Close status rolled up') ? '' : strtoupper($value->closed_reason)),
                'SHIP_TO_ORGANIZATION' => $value->name,
                'SUPPLIER' => $value->vendor_name,
                'INCOMING_VALUE_SUPPLIER_COST' => ($incoming_qty < 0) ? 0 :$incoming_qty * $value->unit_price,
                'INVOICE_VALUE_SUPPLIER_COST' => $value->quantity_billed * $value->unit_price,
                'CANCELLED_VALUE_SUPPLUER_COST' => (intval($value->quantity_cancelled) + $closed_qty) * $value->unit_price,
                'OVER_VALUE_SUPPLIER_COST' => ($over_qty < 0) ? 0 : ($over_qty * $value->unit_price),
                'CURRENT_SRP' => $itemDetails->current_srp,
                'DG_SRP' => $itemDetails->promo_srp,
                'LC' => $itemDetails->landed_cost,
                'WORKING_LC' => $itemDetails->working_landed_cost,
                'BRAND' => $itemDetails->brand_description,
                'PO_VAL_SRP' => $value->po_quantity  * $itemDetails->current_srp,
                'PO_VAL_LC' => $value->po_quantity * $itemDetails->landed_cost,
                'PO_VAL_SUPPLIER_COST' => $value->po_quantity * $value->unit_price,
                'WRR_VAL_SRP' => $value->quantity_received * $itemDetails->current_srp,
                'WRR_VAL_LC' => $value->quantity_received * $itemDetails->landed_cost,
                'WRR_VAL_SUPPLIER_COST' => $value->quantity_received * $value->unit_price,
                'CURRENCY' => $value->currency,
                'PRICE' => $value->unit_price,
                'ORDER_TYPE' => $value->order_type,
                'PO_LESS_CANCELLED' => $actual_po_qty,
                'WRR_QUANTITY' => $value->quantity_received,
                'PO_VALUE_USD' => $actual_po_qty * $value->unit_price,
                'ARRIVED_USD' => $value->quantity_received * $value->unit_price
            ]);
        }

        Excel::create('Export_PO_Report_'.date("Ymd", strtotime($date_from)).'_'.date("Ymd", strtotime($date_to)), function($excel) use ($po_details){
            $excel->sheet('po-report', function($sheet) use ($po_details){
                $headings = array(
                'YEAR',
                'PO NUMBER',
                'DIGITS CODE',
                'IMFS DESCRIPTION',
                'APPROVAL STATUS',
                'HEADER CLOSURE STATUS',
                'LINE CLOSURE STATUS',
                'PO RECEIPT STATUS',
                'ORDER DATE',
                'NEED BY DATE',
                'BUYER',
                'PO QUANTITY',
                'INCOMING QUANTITY',
                'RECEIVED QUANTITY',
                'CANCELLED QUANTITY',
                'OVER QUANTITY',
                'INVOICE QUANTITY',
                'CANCEL REASON',
                'SHIP TO ORGANIZATION',
                'SUPPLIER',
                'INCOMING VALUE @ SUPPLIER COST',
                'INVOICE VALUE @ SUPPLIER COST',
                'CANCELLED VALUE @ SUPPLIER COST',
                'OVER VALUE @ SUPPLIER COST',
                'CURRENT SRP',
                'DG SRP',
                'LC',
                'WORKING LC',
                'BRAND',
                'PO VALUE @ SRP',
                'PO VALUE @ LC',
                'PO VALUE @ SUPPLIER COST',
                'WRR VALUE @ SRP',
                'WRR VALUE @ LC',
                'WRR VALUE @ SUPPLIER COST',
                'CURRENCY',
                'PRICE',
                'ORDER TYPE',
                'PO LESS CANCELLED',
                'WRR QUANTITY',
                'PO VALUE USD',
                'ARRIVED USD');

                $sheet->fromArray($po_details, null, 'A1', false, false);
                $sheet->prependRow(1, $headings);
                $sheet->row(1, function($row) {
                    $row->setBackground('#FFFF00');
                    $row->setAlignment('center');
                });
            });
            
        // })->export('xlsx');
        })->store('xlsx','export');


        return [
            'success' => true,
            'path' =>  asset('export').'/Export_PO_Report_'.date("Ymd", strtotime($date_from)).'_'.date("Ymd", strtotime($date_to)).'.xlsx'
        ];
        
    }

    public function generateIntransitReport(Request $request)
    {
        $store = DB::table('stores')->where('id', CRUDBooster::myStore())->first();
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        Excel::create('Intransit Report - '.date("Ymd H:i:sa"), function($excel) use ($store){  
            $excel->sheet('dr', function($sheet) use ($store){
                // Set auto size for sheet
                $sheet->setAutoSIZE(true);
                $sheet->setColumnFormat(array(
                    'D' => '@',
                ));

                $dr_item = DB::table('ebs_pull')->select(
                    'ebs_pull.order_number',
                    'ebs_pull.dr_number',
                    'ebs_pull.st_document_number as st_number',
                    'ebs_pull.ordered_item as digits_code',
                    'items.upc_code',
                    'items.item_description',
                    'ebs_pull.customer_name as destination',
                    'ebs_pull.transaction_type as transaction_type',
                    'ebs_pull.shipped_quantity as dr_quantity',
                    'ebs_pull.created_at as created_date',
                    'ebs_pull.received_at as received_date',
                    'ebs_pull.status as mostatus',
                    'ebs_pull.is_trade as istrade')
                ->leftJoin('items', 'ebs_pull.ordered_item', '=', 'items.digits_code')->where('ebs_pull.status','PENDING')->where('ebs_pull.is_trade',1);

                if(!CRUDBooster::isSuperAdmin() && !in_array(CRUDBooster::myPrivilegeName(),["Retail Ops","Franchise Ops","Audit","Inventory Control","Merch"])){
                    $dr_item->where('ebs_pull.customer_name',$store->bea_mo_store_name)
                            ->orWhere('ebs_pull.customer_name',$store->bea_so_store_name);
                }
                elseif(in_array(CRUDBooster::myPrivilegeName(),["Franchise Ops"])){
                    $dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FRA');
                }
                elseif (in_array(CRUDBooster::myPrivilegeName(),["Retail Ops"])) {
                    $dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'RTL');
                }
                elseif (in_array(CRUDBooster::myPrivilegeName(),["Rtl Fra Ops"])) {
                    $dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'RTL')
                    ->orWhere(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FRA');
                }
                elseif (in_array(CRUDBooster::myPrivilegeName(),["Rtl Onl Viewer"])) {
                    $dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'RTL')
                    ->orWhere(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FBD')
                    ->orWhere(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FBV');
                }
                elseif (in_array(CRUDBooster::myPrivilegeName(),["Online Ops","Online Viewer"])) {
                    $dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FBD')
                    ->orWhere(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FBV');
                }

                $main_path = url()->current();
                $path = explode('/',$main_path);

                if($path[count($path)-1] == 'DTO'){
                    $dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'RTL')
                            ->orWhere(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FRA');
                }else if($path[count($path)-1] == 'DEO'){
                    $dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FBV')
                            ->orWhere(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FBD');
                }else if($path[count($path)-1] == 'ADM'){
                    $dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '!=', 'RTL')
                            ->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '!=', 'FRA')
                            ->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '!=', 'FBV')
                            ->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '!=', 'FBD')
                            ->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '!=', 'ONL');
                }else if($path[count($path)-1] == 'ALL'){
                    $dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '!=', '');
                }
            
                $datefrom = date("Y-m-d 00:00:00", strtotime($path[count($path)-3]));
                $dateto = date("Y-m-d 23:59:59", strtotime($path[count($path)-2]));
                
                if($path[count($path)-3] != '00-00-00' && $path[count($path)-2] != '00-00-00'){
                    $dr_item->whereBetween('ebs_pull.created_at', [$datefrom, $dateto]);
                }
                
                $dr_item->orderBy('ebs_pull.created_at', 'asc'); 
                $drItems = $dr_item->get(); 

                $headings = array(
                    'SO/MO NUMBER',
                    'DR#',
                    'MO STATUS',
                    'DIGITS CODE',
                    'IMFS DESCRIPTION',
                    'INTRANSIT QTY',
                    'CREATED DATE',
                    'FROM SUBINVENTORY',
                    'CUSTOMER NAME'
                );

                foreach($drItems as $item) {
                    if($path[count($path)-3] != '00-00-00' && $path[count($path)-2] != '00-00-00'){
                        if($item->created_date >= $datefrom && $item->created_date <= $dateto && $item->mostatus == 'PENDING' && $item->istrade == 1)
                        {
                            $from_subinventory = '';
                            $last_word = substr($item->destination, -3);

                            if($last_word == 'RTL'){
                                $from_subinventory = 'RETAIL';
                            }else if($last_word == 'FRA'){
                                $from_subinventory = 'FRANCHISE';
                            }else if($last_word == 'ONL'){
                                $from_subinventory = 'ONLINE';
                            }else if($last_word == 'FBD'){
                                $from_subinventory = 'ONLINE';
                            }else if($last_word == 'FBV'){
                                $from_subinventory = 'ONLINE';
                            }else{
                                $from_subinventory = $last_word;
                            }

                            $mo_status = '';
                            if($item->mostatus == 'PENDING'){
                                $mo_status = 'INTRANSIT';
                            }else{
                                $mo_status = $item->mostatus;
                            }
                            
                            $items_array[] = array(
                                $item->order_number,
                                $item->dr_number,
                                $mo_status,
                                $item->digits_code,
                                $item->item_description,	
                                $item->dr_quantity,
                                date('d-M-y', strtotime($item->created_date)),
                                $from_subinventory,
                                $item->destination
                            );
                        }
                    }else if($path[count($path)-3] == '00-00-00' && $path[count($path)-2] == '00-00-00'){
                        if($item->mostatus == 'PENDING' && $item->istrade == 1)
                        {
                            $from_subinventory = '';
                            $last_word = substr($item->destination, -3);
    
                            if($last_word == 'RTL'){
                                $from_subinventory = 'RETAIL';
                            }else if($last_word == 'FRA'){
                                $from_subinventory = 'FRANCHISE';
                            }else if($last_word == 'ONL'){
                                $from_subinventory = 'ONLINE';
                            }else if($last_word == 'FBD'){
                                $from_subinventory = 'ONLINE';
                            }else if($last_word == 'FBV'){
                                $from_subinventory = 'ONLINE';
                            }else{
                                $from_subinventory = $last_word;
                            }
    
                            $mo_status = '';
                            if($item->mostatus == 'PENDING'){
                                $mo_status = 'INTRANSIT';
                            }else{
                                $mo_status = $item->mostatus;
                            }
                            
                            $items_array[] = array(
                                $item->order_number,
                                $item->dr_number,
                                $mo_status,
                                $item->digits_code,
                                $item->item_description,	
                                $item->dr_quantity,
                                date('d-M-y', strtotime($item->created_date)),
                                $from_subinventory,
                                $item->destination
                            );
                        }
                    }
                }
                
                $sheet->fromArray($items_array, null, 'A1', false, false);
                $sheet->prependRow(1, $headings);
                $sheet->row(1, function($row) {
                    $row->setBackground('#E7F3FD');
                    $row->setBorder('A1', 'thin');
                    $row->setAlignment('center');
                });
                
            });
        })->export('xlsx');
 
        return ['success' => true];
    }
}

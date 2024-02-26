<?php

namespace App\Http\Controllers;

use App\StockStatus;
use Illuminate\Http\Request;
use Session;
use DB;
use CRUDbooster;

class StockStatusController extends Controller
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
        $data['page_title'] = 'Stock Status Report';
        
        //on hand
        $on_hand = DB::connection('oracle')->table('mtl_onhand_quantities_detail')
            ->join('mtl_system_items','mtl_onhand_quantities_detail.inventory_item_id','=','mtl_system_items.inventory_item_id')
            ->select(
                'mtl_system_items.segment1 as digits_code',
                'mtl_system_items.inventory_item_id',
                'mtl_onhand_quantities_detail.subinventory_code as subinventory')
            ->distinct()
            ->where('mtl_onhand_quantities_detail.organization_id',224)
            ->where('mtl_onhand_quantities_detail.subinventory_code','!=','STAGINGMO')
            ->where('mtl_onhand_quantities_detail.subinventory_code','!=','STAGINGSO')
            //->paginate(5000);
            ->get();

        //reserved MO
        $reserved_mo = DB::connection('oracle')->table('MTL_TXN_REQUEST_LINES')
            ->join('MTL_TXN_REQUEST_HEADERS','MTL_TXN_REQUEST_LINES.HEADER_ID','=','MTL_TXN_REQUEST_HEADERS.HEADER_ID')
            ->join('MTL_SYSTEM_ITEMS_B','MTL_TXN_REQUEST_LINES.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
            ->select(
                'MTL_SYSTEM_ITEMS_B.SEGMENT1 as digits_code',
                'MTL_SYSTEM_ITEMS_B.inventory_item_id',
                'MTL_TXN_REQUEST_LINES.FROM_SUBINVENTORY_CODE as subinventory')
            ->distinct()
            ->whereIn('MTL_TXN_REQUEST_LINES.LINE_STATUS', [1,2,3,8])
            ->where('MTL_TXN_REQUEST_LINES.FROM_SUBINVENTORY_CODE','!=','STAGINGMO')
            ->where('MTL_TXN_REQUEST_LINES.FROM_SUBINVENTORY_CODE','!=','STAGINGSO')
            ->where('MTL_TXN_REQUEST_LINES.ORGANIZATION_ID',224)
            ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID',224)
            ->whereNull('MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED')
            // ->where(function($q){
            //     $q->whereNull('MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED')
            //     ->orWhere('MTL_TXN_REQUEST_LINES.QUANTITY','!=', 'MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED');
            // })
            //->union($on_hand)
            //->paginate(5000);
            ->get();

        $merge_1 = $on_hand->merge($reserved_mo);

        //reserve SO
        $reserved_so = DB::connection('oracle')->table('MTL_RESERVATIONS')
            ->join('MTL_SYSTEM_ITEMS_B','MTL_RESERVATIONS.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
            ->select(
                'MTL_SYSTEM_ITEMS_B.SEGMENT1 as digits_code',
                'MTL_SYSTEM_ITEMS_B.inventory_item_id',
                'MTL_RESERVATIONS.SUBINVENTORY_CODE as subinventory')
            ->distinct()
            ->where('MTL_RESERVATIONS.SUBINVENTORY_CODE','!=','STAGINGMO')
            ->where('MTL_RESERVATIONS.SUBINVENTORY_CODE','!=','STAGINGSO')
            ->where('MTL_RESERVATIONS.ORGANIZATION_ID',224)
            ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID',224)
            ->get();
        
        $merge_2 = $merge_1->merge($reserved_so);

        //on order MO
        $on_order_mo = DB::connection('oracle')->table('MTL_ONHAND_TOTAL_MWB_V')
            ->join('MTL_SYSTEM_ITEMS_B','MTL_ONHAND_TOTAL_MWB_V.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
            ->select(DB::raw("MTL_SYSTEM_ITEMS_B.SEGMENT1 as digits_code,
                MTL_SYSTEM_ITEMS_B.inventory_item_id,  
                DECODE(SUBSTR(MTL_ONHAND_TOTAL_MWB_V.LOCATOR,LENGTH(MTL_ONHAND_TOTAL_MWB_V.LOCATOR)-2,3)
                        , 'RTL', 'RETAIL'
                        , 'FRA', 'FRANCHISE'
                        , 'CON', 'DISTRI'
                        , 'CRP', 'DISTRI' 
                        , 'OUT', 'DISTRI'
                        , 'FBV', 'ONLINE FBV' 
                        , 'ONL', 'ONLINE FBV' 
                        , 'EEE','DIGITS'
                        , 'SLE','DIGITS' 
                        , 'DEP','DIGITS') as subinventory"))
            ->distinct()
            ->where('MTL_ONHAND_TOTAL_MWB_V.SUBINVENTORY_CODE','STAGINGMO')
            ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID',224)
            ->get();
        
        $result = $merge_2->merge($on_order_mo);
        

        $data['result'] = $result;

        //Create a view. Please use `cbView` method instead of view method from laravel.
        return view('stock-status.index',$data)->render();
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
     * @param  \App\StockStatus  $stockStatus
     * @return \Illuminate\Http\Response
     */
    public function show(StockStatus $stockStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockStatus  $stockStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(StockStatus $stockStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockStatus  $stockStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockStatus $stockStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockStatus  $stockStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockStatus $stockStatus)
    {
        //
    }
}

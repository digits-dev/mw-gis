<?php

namespace App\Http\Controllers;

use App\OracleMaterialTransaction;
use App\OracleTransactionHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use CRUDbooster;
use PDO;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class EBSPullController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function salesOrderPull() //$datefrom, $dateto
    {
        $datefrom = date("Y-m-d");
        $dateto = date("Y-m-d", strtotime("+1 day"));
            
        $data['sales_order'] = DB::connection('oracle')->table('OE_ORDER_HEADERS_ALL')
            ->join('OE_ORDER_LINES_ALL','OE_ORDER_HEADERS_ALL.HEADER_ID','=','OE_ORDER_LINES_ALL.HEADER_ID')
            ->join('ORG_ORGANIZATION_DEFINITIONS','OE_ORDER_HEADERS_ALL.SHIP_FROM_ORG_ID','=','ORG_ORGANIZATION_DEFINITIONS.ORGANIZATION_ID')
            ->join('WSH_DELIVERY_DETAILS','OE_ORDER_LINES_ALL.LINE_ID','=','WSH_DELIVERY_DETAILS.SOURCE_LINE_ID')
            ->join('MTL_TXN_REQUEST_LINES', 'WSH_DELIVERY_DETAILS.MOVE_ORDER_LINE_ID', '=', 'MTL_TXN_REQUEST_LINES.LINE_ID')
            ->join('WSH_DELIVERY_ASSIGNMENTS','WSH_DELIVERY_DETAILS.DELIVERY_DETAIL_ID','=','WSH_DELIVERY_ASSIGNMENTS.DELIVERY_DETAIL_ID')
            ->join('WSH_NEW_DELIVERIES','WSH_DELIVERY_ASSIGNMENTS.DELIVERY_ID','=','WSH_NEW_DELIVERIES.DELIVERY_ID')
            ->join('HZ_CUST_ACCOUNTS','WSH_DELIVERY_DETAILS.CUSTOMER_ID','=','HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID')
            ->join('HZ_PARTIES','HZ_CUST_ACCOUNTS.PARTY_ID','=','HZ_PARTIES.PARTY_ID')
            ->select(
                'OE_ORDER_HEADERS_ALL.ORDER_NUMBER',
                'OE_ORDER_LINES_ALL.LINE_NUMBER',
                'OE_ORDER_LINES_ALL.ORDERED_ITEM',
                'OE_ORDER_LINES_ALL.ORDERED_QUANTITY',
                'OE_ORDER_LINES_ALL.SHIPPED_QUANTITY',
                'HZ_PARTIES.PARTY_NAME as CUSTOMER_NAME',
                'WSH_NEW_DELIVERIES.CONFIRM_DATE as SHIP_CONFIRMED_DATE',
                'WSH_NEW_DELIVERIES.NAME as DR_NUMBER',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE12 as SERIAL1',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE13 as SERIAL2',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE14 as SERIAL3',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE15 as SERIAL4',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE4 as SERIAL5',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE5 as SERIAL6',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE6 as SERIAL7',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE7 as SERIAL8',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE8 as SERIAL9',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE9 as SERIAL10'
            )
            ->where('OE_ORDER_HEADERS_ALL.ORDER_CATEGORY_CODE', '!=', 'RETURN')
            ->where('ORG_ORGANIZATION_DEFINITIONS.ORGANIZATION_ID', 224)
            ->where('WSH_DELIVERY_DETAILS.INV_INTERFACED_FLAG', 'Y')
            ->where('WSH_DELIVERY_DETAILS.OE_INTERFACED_FLAG', 'Y')
            ->where('WSH_NEW_DELIVERIES.STATUS_CODE', 'IT') //added 2020-11-11
            ->where('HZ_PARTIES.PARTY_NAME','NOT LIKE','%GUAM%')
            ->whereBetween('WSH_NEW_DELIVERIES.CONFIRM_DATE', [$datefrom.' 00:00:00',$dateto.' 00:00:00'])
            ->where(function($query) {
                $query->where(DB::raw('substr(HZ_PARTIES.PARTY_NAME, -3)'), '=', 'RTL')
                ->orWhere(DB::raw('substr(HZ_PARTIES.PARTY_NAME, -3)'), '=', 'FRA');
            })->get();
            
            $record = false;
            foreach ($data['sales_order'] as $key => $value) {
                //checking first
                $itemExists = DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)->where('dr_number',$value->dr_number)->first();
                
                if(!empty($itemExists)){
                    DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)
                        ->where('dr_number',$value->dr_number)
                        ->update([
                            'serial1' => $value->serial1,
                            'serial2' => $value->serial2,
                            'serial3' => $value->serial3,
                            'serial4' => $value->serial4,
                            'serial5' => $value->serial5,
                            'serial6' => $value->serial6,
                            'serial7' => $value->serial7,
                            'serial8' => $value->serial8,
                            'serial9' => $value->serial9,
                            'serial10' => $value->serial10,
                            'serial11' => $value->serial11,
                            'serial12' => $value->serial12,
                            'serial13' => $value->serial13,
                            'serial14' => $value->serial14,
                            'serial15' => $value->serial15
                        ]);
                }
                else {
                    $store = DB::table('stores')->where('bea_so_store_name',$value->customer_name)->where('status','ACTIVE')->first();
                    $ebs_pull_id = DB::table('ebs_pull')->insertGetId(array_merge((array)$value, ['stores_id'=>$store->id,'transaction_type'=>'SO','status'=>'PENDING','created_at'=>date('Y-m-d H:i:s'),'data_pull_date'=>date('Y-m-d')]));
                    //save serial
                    $getSerial = self::getSerialById($ebs_pull_id);
                    $serials = explode(",",$getSerial->serial);
                    foreach ($serials as $serial) {
                        if($serial != ""){
                            DB::table('serials')->insert([
                                'ebs_pull_id'=>$ebs_pull_id, 
                                'serial_number' => $serial,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }                        
                    }
                    $record = true; 
                }
            }
            if($record){
                \Log::info('Sales Order Pull - Done');
            }
    }
    
    public function salesOrderPullManualTime(Request $request) //$datefrom, $dateto
    {
            
        $data['sales_order'] = DB::connection('oracle')->table('OE_ORDER_HEADERS_ALL')
            ->join('OE_ORDER_LINES_ALL','OE_ORDER_HEADERS_ALL.HEADER_ID','=','OE_ORDER_LINES_ALL.HEADER_ID')
            ->join('ORG_ORGANIZATION_DEFINITIONS','OE_ORDER_HEADERS_ALL.SHIP_FROM_ORG_ID','=','ORG_ORGANIZATION_DEFINITIONS.ORGANIZATION_ID')
            ->join('WSH_DELIVERY_DETAILS','OE_ORDER_LINES_ALL.LINE_ID','=','WSH_DELIVERY_DETAILS.SOURCE_LINE_ID')
            ->join('MTL_TXN_REQUEST_LINES', 'WSH_DELIVERY_DETAILS.MOVE_ORDER_LINE_ID', '=', 'MTL_TXN_REQUEST_LINES.LINE_ID')
            ->join('WSH_DELIVERY_ASSIGNMENTS','WSH_DELIVERY_DETAILS.DELIVERY_DETAIL_ID','=','WSH_DELIVERY_ASSIGNMENTS.DELIVERY_DETAIL_ID')
            ->join('WSH_NEW_DELIVERIES','WSH_DELIVERY_ASSIGNMENTS.DELIVERY_ID','=','WSH_NEW_DELIVERIES.DELIVERY_ID')
            ->join('HZ_CUST_ACCOUNTS','WSH_DELIVERY_DETAILS.CUSTOMER_ID','=','HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID')
            ->join('HZ_PARTIES','HZ_CUST_ACCOUNTS.PARTY_ID','=','HZ_PARTIES.PARTY_ID')
            ->select(
                'OE_ORDER_HEADERS_ALL.ORDER_NUMBER',
                'OE_ORDER_LINES_ALL.LINE_NUMBER',
                'OE_ORDER_LINES_ALL.ORDERED_ITEM',
                'OE_ORDER_LINES_ALL.ORDERED_QUANTITY',
                'OE_ORDER_LINES_ALL.SHIPPED_QUANTITY',
                'HZ_PARTIES.PARTY_NAME as CUSTOMER_NAME',
                'WSH_NEW_DELIVERIES.CONFIRM_DATE as SHIP_CONFIRMED_DATE',
                'WSH_NEW_DELIVERIES.NAME as DR_NUMBER',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE12 as SERIAL1',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE13 as SERIAL2',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE14 as SERIAL3',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE15 as SERIAL4',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE4 as SERIAL5',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE5 as SERIAL6',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE6 as SERIAL7',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE7 as SERIAL8',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE8 as SERIAL9',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE9 as SERIAL10'
            )
            ->where('OE_ORDER_HEADERS_ALL.ORDER_CATEGORY_CODE', '!=', 'RETURN')
            ->where('ORG_ORGANIZATION_DEFINITIONS.ORGANIZATION_ID', 224)
            ->where('WSH_DELIVERY_DETAILS.INV_INTERFACED_FLAG', 'Y')
            ->where('WSH_DELIVERY_DETAILS.OE_INTERFACED_FLAG', 'Y')
            ->where('WSH_NEW_DELIVERIES.STATUS_CODE', 'IT') //added 2020-11-11
            ->where('HZ_PARTIES.PARTY_NAME','NOT LIKE','%GUAM%')
            ->whereBetween('WSH_NEW_DELIVERIES.CONFIRM_DATE', [$request->datefrom,$request->dateto])
            ->where(function($query) {
                $query->where(DB::raw('substr(HZ_PARTIES.PARTY_NAME, -3)'), '=', 'RTL')
                ->orWhere(DB::raw('substr(HZ_PARTIES.PARTY_NAME, -3)'), '=', 'FRA');
            })->get();
            
            $record = false;
            foreach ($data['sales_order'] as $key => $value) {
                //checking first
                $itemExists = DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)->where('dr_number',$value->dr_number)->first();
                
                if(!empty($itemExists)){
                    DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)
                        ->where('dr_number',$value->dr_number)
                        ->update([
                            'serial1' => $value->serial1,
                            'serial2' => $value->serial2,
                            'serial3' => $value->serial3,
                            'serial4' => $value->serial4,
                            'serial5' => $value->serial5,
                            'serial6' => $value->serial6,
                            'serial7' => $value->serial7,
                            'serial8' => $value->serial8,
                            'serial9' => $value->serial9,
                            'serial10' => $value->serial10,
                            'serial11' => $value->serial11,
                            'serial12' => $value->serial12,
                            'serial13' => $value->serial13,
                            'serial14' => $value->serial14,
                            'serial15' => $value->serial15
                        ]);
                        $record = true; 
                }
                else {
                    $store = DB::table('stores')->where('bea_so_store_name',$value->customer_name)->where('status','ACTIVE')->first();
                    $ebs_pull_id = DB::table('ebs_pull')->insertGetId(array_merge((array)$value, ['stores_id'=>$store->id,'transaction_type'=>'SO','status'=>'PENDING','created_at'=>date('Y-m-d H:i:s'),'data_pull_date'=>date('Y-m-d')]));
                    //save serial
                    $getSerial = self::getSerialById($ebs_pull_id);
                    $serials = explode(",",$getSerial->serial);
                    foreach ($serials as $serial) {
                        if($serial != ""){
                            DB::table('serials')->insert([
                                'ebs_pull_id'=>$ebs_pull_id, 
                                'serial_number' => $serial,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }                        
                    }
                    $record = true; 
                }
            }
            if($record){
                \Log::info('Sales Order Pull With Time - Done');
                return response()->json(['success' => 'true', 'message' => 'Sales Order Pull with Time - Done']);
            }
    }
    
    public function salesOrderPullManual($datefrom, $dateto)
    {
        $data['sales_order'] = DB::connection('oracle')->table('OE_ORDER_HEADERS_ALL')
            ->join('OE_ORDER_LINES_ALL','OE_ORDER_HEADERS_ALL.HEADER_ID','=','OE_ORDER_LINES_ALL.HEADER_ID')
            ->join('ORG_ORGANIZATION_DEFINITIONS','OE_ORDER_HEADERS_ALL.SHIP_FROM_ORG_ID','=','ORG_ORGANIZATION_DEFINITIONS.ORGANIZATION_ID')
            ->join('WSH_DELIVERY_DETAILS','OE_ORDER_LINES_ALL.LINE_ID','=','WSH_DELIVERY_DETAILS.SOURCE_LINE_ID')
            ->join('MTL_TXN_REQUEST_LINES', 'WSH_DELIVERY_DETAILS.MOVE_ORDER_LINE_ID', '=', 'MTL_TXN_REQUEST_LINES.LINE_ID')
            ->join('WSH_DELIVERY_ASSIGNMENTS','WSH_DELIVERY_DETAILS.DELIVERY_DETAIL_ID','=','WSH_DELIVERY_ASSIGNMENTS.DELIVERY_DETAIL_ID')
            ->join('WSH_NEW_DELIVERIES','WSH_DELIVERY_ASSIGNMENTS.DELIVERY_ID','=','WSH_NEW_DELIVERIES.DELIVERY_ID')
            ->join('HZ_CUST_ACCOUNTS','WSH_DELIVERY_DETAILS.CUSTOMER_ID','=','HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID')
            ->join('HZ_PARTIES','HZ_CUST_ACCOUNTS.PARTY_ID','=','HZ_PARTIES.PARTY_ID')
            ->select(
                'OE_ORDER_HEADERS_ALL.ORDER_NUMBER',
                'OE_ORDER_LINES_ALL.LINE_NUMBER',
                'OE_ORDER_LINES_ALL.ORDERED_ITEM',
                'OE_ORDER_LINES_ALL.ORDERED_QUANTITY',
                'OE_ORDER_LINES_ALL.SHIPPED_QUANTITY',
                'HZ_PARTIES.PARTY_NAME as CUSTOMER_NAME',
                'WSH_NEW_DELIVERIES.CONFIRM_DATE as SHIP_CONFIRMED_DATE',
                'WSH_NEW_DELIVERIES.NAME as DR_NUMBER',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE12 as SERIAL1',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE13 as SERIAL2',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE14 as SERIAL3',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE15 as SERIAL4',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE4 as SERIAL5',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE5 as SERIAL6',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE6 as SERIAL7',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE7 as SERIAL8',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE8 as SERIAL9',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE9 as SERIAL10'
            )
            ->where('OE_ORDER_HEADERS_ALL.ORDER_CATEGORY_CODE', '!=', 'RETURN')
            ->where('ORG_ORGANIZATION_DEFINITIONS.ORGANIZATION_ID', 224)
            ->where('WSH_DELIVERY_DETAILS.INV_INTERFACED_FLAG', 'Y')
            ->where('WSH_DELIVERY_DETAILS.OE_INTERFACED_FLAG', 'Y')
            ->where('WSH_NEW_DELIVERIES.STATUS_CODE', 'IT') //added 2020-11-11
            ->where('HZ_PARTIES.PARTY_NAME','NOT LIKE','%GUAM%')
            ->whereBetween('WSH_NEW_DELIVERIES.CONFIRM_DATE', [$datefrom.' 00:00:00',$dateto.' 23:59:59'])
            ->where(function($query) {
                $query->where(DB::raw('substr(HZ_PARTIES.PARTY_NAME, -3)'), '=', 'RTL')
                ->orWhere(DB::raw('substr(HZ_PARTIES.PARTY_NAME, -3)'), '=', 'FRA');
            })
            // ->where('WSH_NEW_DELIVERIES.NAME','30179973')
            ->get();
            
            $record = false;
            foreach ($data['sales_order'] as $key => $value) {
                //checking first
                $itemExists = DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)->where('dr_number',$value->dr_number)->first();
                
                if(!empty($itemExists)){
                    DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)
                        ->where('dr_number',$value->dr_number)
                        ->update([
                            'serial1' => $value->serial1,
                            'serial2' => $value->serial2,
                            'serial3' => $value->serial3,
                            'serial4' => $value->serial4,
                            'serial5' => $value->serial5,
                            'serial6' => $value->serial6,
                            'serial7' => $value->serial7,
                            'serial8' => $value->serial8,
                            'serial9' => $value->serial9,
                            'serial10' => $value->serial10,
                            'serial11' => $value->serial11,
                            'serial12' => $value->serial12,
                            'serial13' => $value->serial13,
                            'serial14' => $value->serial14,
                            'serial15' => $value->serial15
                        ]);
                }
                else {
                    $store = DB::table('stores')->where('bea_so_store_name',$value->customer_name)->where('status','ACTIVE')->first();
                    $ebs_pull_id = DB::table('ebs_pull')->insertGetId(array_merge((array)$value, ['stores_id'=>$store->id,'transaction_type'=>'SO','status'=>'PENDING','created_at'=>date('Y-m-d H:i:s'),'data_pull_date'=>date('Y-m-d')]));
                    //save serial
                    $getSerial = self::getSerialById($ebs_pull_id);
                    $serials = explode(",",$getSerial->serial);
                    foreach ($serials as $serial) {
                        if($serial != ""){
                            DB::table('serials')->insert([
                                'ebs_pull_id'=>$ebs_pull_id, 
                                'serial_number' => $serial,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }                        
                    }
                    $record = true; 
                }
            }
            if($record){
                \Log::info('Sales Order Pull - Done');
            }
    }

    public function salesOrderPullHQ()
    {
        $datefrom = date("Y-m-d");
        $dateto = date("Y-m-d", strtotime("+1 day"));
            
        $data['sales_order'] = DB::connection('oracle')->table('OE_ORDER_HEADERS_ALL')
            ->join('OE_ORDER_LINES_ALL','OE_ORDER_HEADERS_ALL.HEADER_ID','=','OE_ORDER_LINES_ALL.HEADER_ID')
            ->join('ORG_ORGANIZATION_DEFINITIONS','OE_ORDER_HEADERS_ALL.SHIP_FROM_ORG_ID','=','ORG_ORGANIZATION_DEFINITIONS.ORGANIZATION_ID')
            ->join('WSH_DELIVERY_DETAILS','OE_ORDER_LINES_ALL.LINE_ID','=','WSH_DELIVERY_DETAILS.SOURCE_LINE_ID')
            ->join('MTL_TXN_REQUEST_LINES', 'WSH_DELIVERY_DETAILS.MOVE_ORDER_LINE_ID', '=', 'MTL_TXN_REQUEST_LINES.LINE_ID')
            ->join('WSH_DELIVERY_ASSIGNMENTS','WSH_DELIVERY_DETAILS.DELIVERY_DETAIL_ID','=','WSH_DELIVERY_ASSIGNMENTS.DELIVERY_DETAIL_ID')
            ->join('WSH_NEW_DELIVERIES','WSH_DELIVERY_ASSIGNMENTS.DELIVERY_ID','=','WSH_NEW_DELIVERIES.DELIVERY_ID')
            ->join('HZ_CUST_ACCOUNTS','WSH_DELIVERY_DETAILS.CUSTOMER_ID','=','HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID')
            ->join('HZ_PARTIES','HZ_CUST_ACCOUNTS.PARTY_ID','=','HZ_PARTIES.PARTY_ID')
            ->select(
                'OE_ORDER_HEADERS_ALL.ORDER_NUMBER',
                'OE_ORDER_LINES_ALL.LINE_NUMBER',
                'OE_ORDER_LINES_ALL.ORDERED_ITEM',
                'OE_ORDER_LINES_ALL.ORDERED_QUANTITY',
                'OE_ORDER_LINES_ALL.SHIPPED_QUANTITY',
                'HZ_PARTIES.PARTY_NAME as CUSTOMER_NAME',
                'WSH_NEW_DELIVERIES.CONFIRM_DATE as SHIP_CONFIRMED_DATE',
                'WSH_NEW_DELIVERIES.NAME as DR_NUMBER',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE12 as SERIAL1',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE13 as SERIAL2',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE14 as SERIAL3',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE15 as SERIAL4',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE4 as SERIAL5',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE5 as SERIAL6',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE6 as SERIAL7',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE7 as SERIAL8',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE8 as SERIAL9',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE9 as SERIAL10'
            )
            ->where('OE_ORDER_HEADERS_ALL.ORDER_CATEGORY_CODE', '!=', 'RETURN')
            ->where('ORG_ORGANIZATION_DEFINITIONS.ORGANIZATION_ID', 224)
            ->where('WSH_DELIVERY_DETAILS.INV_INTERFACED_FLAG', 'Y')
            ->where('WSH_DELIVERY_DETAILS.OE_INTERFACED_FLAG', 'Y')
            ->where('WSH_NEW_DELIVERIES.STATUS_CODE', 'IT') //added 2020-11-11
            ->where('HZ_PARTIES.PARTY_NAME','NOT LIKE','%GUAM%')
            ->whereBetween('WSH_NEW_DELIVERIES.CONFIRM_DATE', [$datefrom.' 00:00:00',$dateto.' 00:00:00'])
            ->where(function($query) {
                $query->where(DB::raw('substr(HZ_PARTIES.PARTY_NAME, -3)'), '=', 'EEE')
                ->orWhere(DB::raw('substr(HZ_PARTIES.PARTY_NAME, -3)'), '=', 'DEP')
                ->orWhere(DB::raw('substr(HZ_PARTIES.PARTY_NAME, -3)'), '=', 'OUT')
                ->orWhere(DB::raw('substr(HZ_PARTIES.PARTY_NAME, -3)'), '=', 'CRP')
                ->orWhere(DB::raw('substr(HZ_PARTIES.PARTY_NAME, -3)'), '=', 'DIG');
            })->get();
            
            $record = false;
            foreach ($data['sales_order'] as $key => $value) {
                //checking first
                $itemExists = DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)->where('dr_number',$value->dr_number)->first();
                
                if(!empty($itemExists)){
                    DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)
                        ->where('dr_number',$value->dr_number)
                        ->update([
                            'serial1' => $value->serial1,
                            'serial2' => $value->serial2,
                            'serial3' => $value->serial3,
                            'serial4' => $value->serial4,
                            'serial5' => $value->serial5,
                            'serial6' => $value->serial6,
                            'serial7' => $value->serial7,
                            'serial8' => $value->serial8,
                            'serial9' => $value->serial9,
                            'serial10' => $value->serial10,
                            'serial11' => $value->serial11,
                            'serial12' => $value->serial12,
                            'serial13' => $value->serial13,
                            'serial14' => $value->serial14,
                            'serial15' => $value->serial15
                        ]);
                }
                else {
                    $store = DB::table('stores')->where('bea_so_store_name',$value->customer_name)->where('status','ACTIVE')->first();
                    $ebs_pull_id = DB::table('ebs_pull')->insertGetId(array_merge((array)$value, ['stores_id'=>$store->id,'transaction_type'=>'SO','status'=>'PENDING','created_at'=>date('Y-m-d H:i:s'),'data_pull_date'=>date('Y-m-d')]));
                    //save serial
                    $getSerial = self::getSerialById($ebs_pull_id);
                    $serials = explode(",",$getSerial->serial);
                    foreach ($serials as $serial) {
                        if($serial != ""){
                            DB::table('serials')->insert([
                                'ebs_pull_id'=>$ebs_pull_id, 
                                'serial_number' => $serial,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }                        
                    }
                    $record = true; 
                }
            }
            if($record){
                \Log::info('Sales Order Pull HQ - Done');
            }
            
    }

    public function salesOrderPullAdmin()
    {
        // $datefrom = date("Y-m-d");
        // $dateto = date("Y-m-d", strtotime("+1 day"));
        
        $datefrom = date("Y-m-d H:i:s", strtotime("-5 hour"));
        $dateto = date("Y-m-d H:i:s", strtotime("-1 hour"));
            
        $data['sales_order'] = DB::connection('oracle')->table('OE_ORDER_HEADERS_ALL')
            ->join('OE_ORDER_LINES_ALL','OE_ORDER_HEADERS_ALL.HEADER_ID','=','OE_ORDER_LINES_ALL.HEADER_ID')
            ->join('ORG_ORGANIZATION_DEFINITIONS','OE_ORDER_HEADERS_ALL.SHIP_FROM_ORG_ID','=','ORG_ORGANIZATION_DEFINITIONS.ORGANIZATION_ID')
            ->join('WSH_DELIVERY_DETAILS','OE_ORDER_LINES_ALL.LINE_ID','=','WSH_DELIVERY_DETAILS.SOURCE_LINE_ID')
            ->join('MTL_TXN_REQUEST_LINES', 'WSH_DELIVERY_DETAILS.MOVE_ORDER_LINE_ID', '=', 'MTL_TXN_REQUEST_LINES.LINE_ID')
            ->join('WSH_DELIVERY_ASSIGNMENTS','WSH_DELIVERY_DETAILS.DELIVERY_DETAIL_ID','=','WSH_DELIVERY_ASSIGNMENTS.DELIVERY_DETAIL_ID')
            ->join('WSH_NEW_DELIVERIES','WSH_DELIVERY_ASSIGNMENTS.DELIVERY_ID','=','WSH_NEW_DELIVERIES.DELIVERY_ID')
            ->join('HZ_CUST_ACCOUNTS','WSH_DELIVERY_DETAILS.CUSTOMER_ID','=','HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID')
            ->join('HZ_PARTIES','HZ_CUST_ACCOUNTS.PARTY_ID','=','HZ_PARTIES.PARTY_ID')
            ->join('OE_TRANSACTION_TYPES_TL','OE_ORDER_HEADERS_ALL.ORDER_TYPE_ID','=','OE_TRANSACTION_TYPES_TL.TRANSACTION_TYPE_ID')
            ->select(
                'OE_ORDER_HEADERS_ALL.ORDER_NUMBER',
                'OE_ORDER_LINES_ALL.LINE_NUMBER',
                'OE_ORDER_LINES_ALL.ORDERED_ITEM',
                'OE_ORDER_LINES_ALL.ORDERED_QUANTITY',
                'OE_ORDER_LINES_ALL.SHIPPED_QUANTITY',
                'HZ_PARTIES.PARTY_NAME as CUSTOMER_NAME',
                'WSH_NEW_DELIVERIES.CONFIRM_DATE as SHIP_CONFIRMED_DATE',
                'WSH_NEW_DELIVERIES.NAME as DR_NUMBER',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE12 as SERIAL1',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE13 as SERIAL2',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE14 as SERIAL3',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE15 as SERIAL4',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE4 as SERIAL5',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE5 as SERIAL6',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE6 as SERIAL7',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE7 as SERIAL8',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE8 as SERIAL9',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE9 as SERIAL10'
            )
            ->where('OE_ORDER_HEADERS_ALL.ORDER_CATEGORY_CODE', '!=', 'RETURN')
            ->where('ORG_ORGANIZATION_DEFINITIONS.ORGANIZATION_ID', 243)
            ->where('WSH_DELIVERY_DETAILS.INV_INTERFACED_FLAG', 'Y')
            ->where('WSH_DELIVERY_DETAILS.OE_INTERFACED_FLAG', 'Y')
            ->where('WSH_NEW_DELIVERIES.STATUS_CODE', 'IT') //added 2020-11-11
            ->where('HZ_PARTIES.PARTY_NAME','NOT LIKE','%GUAM%')
            ->where('OE_TRANSACTION_TYPES_TL.NAME','ADMIN PAPER BAGS') //edit when prod (ADMIN PAPER BAGS) dev (PAPER BAG SALES)
            // ->whereBetween('WSH_NEW_DELIVERIES.CONFIRM_DATE', [$datefrom.' 00:00:00',$dateto.' 00:00:00'])
            ->whereBetween('WSH_NEW_DELIVERIES.CONFIRM_DATE', [$datefrom, $dateto])
            ->where(function($query) {
                $query->where(DB::raw('substr(HZ_PARTIES.PARTY_NAME, -3)'), '=', 'RTL')
                ->orWhere(DB::raw('substr(HZ_PARTIES.PARTY_NAME, -3)'), '=', 'FRA');
            })->get();
            
            $record = false;
            foreach ($data['sales_order'] as $key => $value) {
                //checking first
                $itemExists = DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)->where('dr_number',$value->dr_number)->first();
                
                if(!empty($itemExists)){
                    DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)
                        ->where('dr_number',$value->dr_number)
                        ->update([
                            'serial1' => $value->serial1,
                            'serial2' => $value->serial2,
                            'serial3' => $value->serial3,
                            'serial4' => $value->serial4,
                            'serial5' => $value->serial5,
                            'serial6' => $value->serial6,
                            'serial7' => $value->serial7,
                            'serial8' => $value->serial8,
                            'serial9' => $value->serial9,
                            'serial10' => $value->serial10,
                            'serial11' => $value->serial11,
                            'serial12' => $value->serial12,
                            'serial13' => $value->serial13,
                            'serial14' => $value->serial14,
                            'serial15' => $value->serial15
                        ]);
                }
                else {
                    $store = DB::table('stores')->where('bea_so_store_name',$value->customer_name)->where('status','ACTIVE')->first();
                    $ebs_pull_id = DB::table('ebs_pull')->insertGetId(array_merge((array)$value, ['stores_id'=>$store->id,'is_trade'=>0,'transaction_type'=>'SO','status'=>'PENDING','created_at'=>date('Y-m-d H:i:s'),'data_pull_date'=>date('Y-m-d')]));
                    //save serial
                    $getSerial = self::getSerialById($ebs_pull_id);
                    $serials = explode(",",$getSerial->serial);
                    foreach ($serials as $serial) {
                        if($serial != ""){
                            DB::table('serials')->insert([
                                'ebs_pull_id'=>$ebs_pull_id, 
                                'serial_number' => $serial,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }                        
                    }
                    $record = true; 
                }
            }
            if($record){
                \Log::info('Sales Order Pull Admin - Done');
            }
    }
    
    public function salesOrderPullAdminManual($datefrom,$dateto)
    {
            
        $data['sales_order'] = DB::connection('oracle')->table('OE_ORDER_HEADERS_ALL')
            ->join('OE_ORDER_LINES_ALL','OE_ORDER_HEADERS_ALL.HEADER_ID','=','OE_ORDER_LINES_ALL.HEADER_ID')
            ->join('ORG_ORGANIZATION_DEFINITIONS','OE_ORDER_HEADERS_ALL.SHIP_FROM_ORG_ID','=','ORG_ORGANIZATION_DEFINITIONS.ORGANIZATION_ID')
            ->join('WSH_DELIVERY_DETAILS','OE_ORDER_LINES_ALL.LINE_ID','=','WSH_DELIVERY_DETAILS.SOURCE_LINE_ID')
            ->join('MTL_TXN_REQUEST_LINES', 'WSH_DELIVERY_DETAILS.MOVE_ORDER_LINE_ID', '=', 'MTL_TXN_REQUEST_LINES.LINE_ID')
            ->join('WSH_DELIVERY_ASSIGNMENTS','WSH_DELIVERY_DETAILS.DELIVERY_DETAIL_ID','=','WSH_DELIVERY_ASSIGNMENTS.DELIVERY_DETAIL_ID')
            ->join('WSH_NEW_DELIVERIES','WSH_DELIVERY_ASSIGNMENTS.DELIVERY_ID','=','WSH_NEW_DELIVERIES.DELIVERY_ID')
            ->join('HZ_CUST_ACCOUNTS','WSH_DELIVERY_DETAILS.CUSTOMER_ID','=','HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID')
            ->join('HZ_PARTIES','HZ_CUST_ACCOUNTS.PARTY_ID','=','HZ_PARTIES.PARTY_ID')
            ->join('OE_TRANSACTION_TYPES_TL','OE_ORDER_HEADERS_ALL.ORDER_TYPE_ID','=','OE_TRANSACTION_TYPES_TL.TRANSACTION_TYPE_ID')
            ->select(
                'OE_ORDER_HEADERS_ALL.ORDER_NUMBER',
                'OE_ORDER_LINES_ALL.LINE_NUMBER',
                'OE_ORDER_LINES_ALL.ORDERED_ITEM',
                'OE_ORDER_LINES_ALL.ORDERED_QUANTITY',
                'OE_ORDER_LINES_ALL.SHIPPED_QUANTITY',
                'HZ_PARTIES.PARTY_NAME as CUSTOMER_NAME',
                'WSH_NEW_DELIVERIES.CONFIRM_DATE as SHIP_CONFIRMED_DATE',
                'WSH_NEW_DELIVERIES.NAME as DR_NUMBER',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE12 as SERIAL1',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE13 as SERIAL2',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE14 as SERIAL3',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE15 as SERIAL4',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE4 as SERIAL5',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE5 as SERIAL6',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE6 as SERIAL7',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE7 as SERIAL8',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE8 as SERIAL9',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE9 as SERIAL10'
            )
            ->where('OE_ORDER_HEADERS_ALL.ORDER_CATEGORY_CODE', '!=', 'RETURN')
            ->where('ORG_ORGANIZATION_DEFINITIONS.ORGANIZATION_ID', 243)
            ->where('WSH_DELIVERY_DETAILS.INV_INTERFACED_FLAG', 'Y')
            ->where('WSH_DELIVERY_DETAILS.OE_INTERFACED_FLAG', 'Y')
            ->where('WSH_NEW_DELIVERIES.STATUS_CODE', 'IT') //added 2020-11-11
            ->where('HZ_PARTIES.PARTY_NAME','NOT LIKE','%GUAM%')
            ->where('OE_TRANSACTION_TYPES_TL.NAME','ADMIN PAPER BAGS') //edit when prod (ADMIN PAPER BAGS) dev (PAPER BAG SALES)
            ->whereBetween('WSH_NEW_DELIVERIES.CONFIRM_DATE', [$datefrom.' 00:00:00',$dateto.' 23:59:59'])
            ->where(function($query) {
                $query->where(DB::raw('substr(HZ_PARTIES.PARTY_NAME, -3)'), '=', 'RTL')
                ->orWhere(DB::raw('substr(HZ_PARTIES.PARTY_NAME, -3)'), '=', 'FRA');
            })->get();
            
            $record = false;
            foreach ($data['sales_order'] as $key => $value) {
                //checking first
                $itemExists = DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)->where('dr_number',$value->dr_number)->first();
                
                if(!empty($itemExists)){
                    DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)
                        ->where('dr_number',$value->dr_number)
                        ->update([
                            'serial1' => $value->serial1,
                            'serial2' => $value->serial2,
                            'serial3' => $value->serial3,
                            'serial4' => $value->serial4,
                            'serial5' => $value->serial5,
                            'serial6' => $value->serial6,
                            'serial7' => $value->serial7,
                            'serial8' => $value->serial8,
                            'serial9' => $value->serial9,
                            'serial10' => $value->serial10,
                            'serial11' => $value->serial11,
                            'serial12' => $value->serial12,
                            'serial13' => $value->serial13,
                            'serial14' => $value->serial14,
                            'serial15' => $value->serial15
                        ]);
                }
                else {
                    $store = DB::table('stores')->where('bea_so_store_name',$value->customer_name)->where('status','ACTIVE')->first();
                    $ebs_pull_id = DB::table('ebs_pull')->insertGetId(array_merge((array)$value, ['stores_id'=>$store->id,'is_trade'=>0,'transaction_type'=>'SO','status'=>'PENDING','created_at'=>date('Y-m-d H:i:s'),'data_pull_date'=>date('Y-m-d')]));
                    //save serial
                    $getSerial = self::getSerialById($ebs_pull_id);
                    $serials = explode(",",$getSerial->serial);
                    foreach ($serials as $serial) {
                        if($serial != ""){
                            DB::table('serials')->insert([
                                'ebs_pull_id'=>$ebs_pull_id, 
                                'serial_number' => $serial,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }                        
                    }
                    $record = true; 
                }
            }
            if($record){
                \Log::info('Sales Order Pull Admin - Done');
            }
    }

    public function moveOrderPull() //$datefrom, $dateto
    {
        $datefrom = date("Y-m-d H:i:s", strtotime("-5 hour"));
        $dateto = date("Y-m-d H:i:s", strtotime("-1 hour"));

        $request_numbers = array();
        $shipment_numbers = OracleMaterialTransaction::getShipments($datefrom,$dateto,'DTO')->get();

    /*
        // $shipment_numbers = DB::connection('oracle')->table('MTL_MATERIAL_TRANSACTIONS')
        //     ->join('MTL_TXN_REQUEST_HEADERS','MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID','=','MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER')
        //     ->select('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID as SHIPMENT_NUMBER')
        //     ->whereBetween('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_DATE', [$datefrom, $dateto])
        //     // ->whereBetween('MTL_TXN_REQUEST_HEADERS.CREATION_DATE', [$datefrom, $dateto])
        //     ->where('MTL_MATERIAL_TRANSACTIONS.ORGANIZATION_ID', 224)
        //     ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 224)
        //     ->where('MTL_MATERIAL_TRANSACTIONS.SUBINVENTORY_CODE', 'STAGINGMO')
        //     ->where('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_TYPE_ID', 64)
        //     ->distinct()->get();
    */    
    
        foreach ($shipment_numbers as $key => $value) {
            array_push($request_numbers, $value->shipment_number);
        }

        $data['move_order'] = OracleTransactionHeader::getMoveOrders($request_numbers,'DTO')->where(function($query) {
            $query->where(DB::raw('substr(MTL_ITEM_LOCATIONS.SEGMENT2, -3)'), '=', 'RTL')
            ->orWhere(DB::raw('substr(MTL_ITEM_LOCATIONS.SEGMENT2, -3)'), '=', 'FRA');
        })->get();

        /*
            $data['move_order'] = DB::connection('oracle')->table('MTL_TXN_REQUEST_HEADERS')
                ->join('MTL_TXN_REQUEST_LINES','MTL_TXN_REQUEST_HEADERS.HEADER_ID','=','MTL_TXN_REQUEST_LINES.HEADER_ID')
                ->join('MTL_SYSTEM_ITEMS_B','MTL_TXN_REQUEST_LINES.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
                //->join('RCV_SHIPMENT_HEADERS','MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER','=','RCV_SHIPMENT_HEADERS.SHIPMENT_NUM') //removed 2020-12-03
                ->join('MTL_ITEM_LOCATIONS','MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID','=','MTL_ITEM_LOCATIONS.INVENTORY_LOCATION_ID')
                ->select(
                    'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as ORDER_NUMBER',
                    'MTL_TXN_REQUEST_LINES.LINE_NUMBER',
                    'MTL_SYSTEM_ITEMS_B.SEGMENT1 as ORDERED_ITEM',
                    'MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID as ORDERED_ITEM_ID',
                    'MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED as SHIPPED_QUANTITY',
                    'MTL_ITEM_LOCATIONS.SEGMENT2 as CUSTOMER_NAME',
                    'MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID as LOCATOR_ID',
                    //'RCV_SHIPMENT_HEADERS.SHIPPED_DATE as SHIP_CONFIRMED_DATE', //removed 2020-12-03
                    'MTL_TXN_REQUEST_HEADERS.DESCRIPTION as SHIPPING_INSTRUCTION', //added 2021-03-05
                    'MTL_TXN_REQUEST_LINES.REFERENCE as CUSTOMER_PO', //added 2022-05-02
                    'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as DR_NUMBER',
                    'MTL_TXN_REQUEST_LINES.ATTRIBUTE12 as SERIAL1',
                    'MTL_TXN_REQUEST_LINES.ATTRIBUTE13 as SERIAL2',
                    'MTL_TXN_REQUEST_LINES.ATTRIBUTE14 as SERIAL3',
                    'MTL_TXN_REQUEST_LINES.ATTRIBUTE15 as SERIAL4',
                    'MTL_TXN_REQUEST_LINES.ATTRIBUTE4 as SERIAL5',
                    'MTL_TXN_REQUEST_LINES.ATTRIBUTE5 as SERIAL6',
                    'MTL_TXN_REQUEST_LINES.ATTRIBUTE6 as SERIAL7',
                    'MTL_TXN_REQUEST_LINES.ATTRIBUTE7 as SERIAL8',
                    'MTL_TXN_REQUEST_LINES.ATTRIBUTE8 as SERIAL9',
                    'MTL_TXN_REQUEST_LINES.ATTRIBUTE9 as SERIAL10'
                )
                ->where('MTL_TXN_REQUEST_HEADERS.MOVE_ORDER_TYPE', 1)
                ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 224)
                ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID', 223)
                ->whereNotNull('MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED')
                ->where('MTL_TXN_REQUEST_LINES.LINE_STATUS','!=', '6')
                ->whereIn('MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER', array_values($request_numbers))
                ->where(function($query) {
                    $query->where(\DB::raw('substr(MTL_ITEM_LOCATIONS.SEGMENT2, -3)'), '=', 'RTL')
                    ->orWhere(\DB::raw('substr(MTL_ITEM_LOCATIONS.SEGMENT2, -3)'), '=', 'FRA');
                })
                ->get();
        */
        
        $record = false;
        foreach ($data['move_order'] as $key => $value) {
            $itemExists = DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)->where('dr_number',$value->dr_number)->first();
                
            if(!empty($itemExists)){
                DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)
                    ->where('dr_number',$value->dr_number)
                    ->update([
                        'serial1' => $value->serial1,
                        'serial2' => $value->serial2,
                        'serial3' => $value->serial3,
                        'serial4' => $value->serial4,
                        'serial5' => $value->serial5,
                        'serial6' => $value->serial6,
                        'serial7' => $value->serial7,
                        'serial8' => $value->serial8,
                        'serial9' => $value->serial9,
                        'serial10' => $value->serial10
                    ]);
            }
            else {
                $store = DB::table('stores')
                    ->where('bea_mo_store_name',$value->customer_name)
                    ->where('status','ACTIVE')->first();
                $ebs_pull_id = DB::table('ebs_pull')->insertGetId(array_merge((array)$value,['stores_id'=>$store->id,'transaction_type'=>'MO','status'=>'PROCESSING','created_at'=>date('Y-m-d H:i:s'),'data_pull_date'=>date('Y-m-d')]));
                $getSerial = self::getSerialById($ebs_pull_id);
                $serials = explode(",",$getSerial->serial);
                foreach ($serials as $serial) {
                    if($serial != ""){
                        DB::table('serials')->insert([
                            'ebs_pull_id' => $ebs_pull_id, 
                            'serial_number' => $serial,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
                $customer_name = DB::table('stores')
                    ->where('bea_mo_store_name', $value->customer_name)
                    ->where('status','ACTIVE')
                    ->select('doo_subinventory')->first();
                //autocreate DOT 
                app(EBSPushController::class)->createDOT($value->dr_number, $value->ordered_item_id, $value->shipped_quantity, $customer_name->doo_subinventory, $value->locator_id);
                $record = true;
            }
        }
        if($record){
            // app(EBSPushController::class)->processTransactionInterface();
            \Log::info('Move Order Pull - Done');
        }
    }
    
    public function moveOrderPullRma()
    {
        $datefrom = date("Y-m-d H:i:s", strtotime("-5 hour"));
        $dateto = date("Y-m-d H:i:s", strtotime("-1 hour"));
        
        // $datefrom = '2024-01-10 00:00:00';
        // $dateto = '2024-01-10 23:23:23';

        $request_numbers = array();

        $shipment_numbers = OracleMaterialTransaction::getShipments($datefrom,$dateto,'RMA')->get();

        // $shipment_numbers = DB::connection('oracle')->table('MTL_MATERIAL_TRANSACTIONS')
        //     ->join('MTL_TXN_REQUEST_HEADERS','MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID','=','MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER')
        //     ->select('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID as SHIPMENT_NUMBER')
        //     ->whereBetween('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_DATE', [$datefrom, $dateto])
        //     ->where('MTL_MATERIAL_TRANSACTIONS.ORGANIZATION_ID', 225)
        //     ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 225)
        //     ->where('MTL_MATERIAL_TRANSACTIONS.SUBINVENTORY_CODE', 'STAGINGMO')
        //     ->where('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_TYPE_ID', 64)
        //     ->distinct()->get();
            
        foreach ($shipment_numbers as $key => $value) {
            array_push($request_numbers, $value->shipment_number);
        }

        $data['move_order'] = OracleTransactionHeader::getMoveOrders($request_numbers,'RMA')->where(function($query) {
            $query->where(\DB::raw('substr(MTL_ITEM_LOCATIONS.SEGMENT2, -3)'), 'LIKE', 'RTL');
        })
        ->get();

    /*    
        $data['move_order'] = DB::connection('oracle')->table('MTL_TXN_REQUEST_HEADERS')
            ->join('MTL_TXN_REQUEST_LINES','MTL_TXN_REQUEST_HEADERS.HEADER_ID','=','MTL_TXN_REQUEST_LINES.HEADER_ID')
            ->join('MTL_SYSTEM_ITEMS_B','MTL_TXN_REQUEST_LINES.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
            ->join('MTL_ITEM_LOCATIONS','MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID','=','MTL_ITEM_LOCATIONS.INVENTORY_LOCATION_ID')
            ->select(
                'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as ORDER_NUMBER',
                'MTL_TXN_REQUEST_LINES.LINE_NUMBER',
                'MTL_SYSTEM_ITEMS_B.SEGMENT1 as ORDERED_ITEM',
                'MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID as ORDERED_ITEM_ID',
                'MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED as SHIPPED_QUANTITY',
                'MTL_ITEM_LOCATIONS.SEGMENT2 as CUSTOMER_NAME',
                'MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID as LOCATOR_ID',
                'MTL_TXN_REQUEST_HEADERS.DESCRIPTION as SHIPPING_INSTRUCTION', 
                'MTL_TXN_REQUEST_LINES.REFERENCE as CUSTOMER_PO', 
                'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as DR_NUMBER',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE12 as SERIAL1',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE13 as SERIAL2',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE14 as SERIAL3',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE15 as SERIAL4',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE4 as SERIAL5',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE5 as SERIAL6',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE6 as SERIAL7',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE7 as SERIAL8',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE8 as SERIAL9',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE9 as SERIAL10'
            )
            ->where('MTL_TXN_REQUEST_HEADERS.MOVE_ORDER_TYPE', 1)
            ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 225)
            ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID', 223)
            ->whereNotNull('MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED')
            ->where('MTL_TXN_REQUEST_LINES.LINE_STATUS','!=', '6')
            ->whereIn('MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER', array_values($request_numbers))
            ->where(function($query) {
                $query->where(\DB::raw('substr(MTL_ITEM_LOCATIONS.SEGMENT2, -3)'), 'LIKE', 'RTL');
            })
            ->get();
    */        
        $record = false;
        foreach ($data['move_order'] as $key => $value) {
            $itemExists = DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)->where('dr_number',$value->dr_number)->first();
                
            if(!empty($itemExists)){
                DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)
                    ->where('dr_number',$value->dr_number)
                    ->update([
                        'serial1' => $value->serial1,
                        'serial2' => $value->serial2,
                        'serial3' => $value->serial3,
                        'serial4' => $value->serial4,
                        'serial5' => $value->serial5,
                        'serial6' => $value->serial6,
                        'serial7' => $value->serial7,
                        'serial8' => $value->serial8,
                        'serial9' => $value->serial9,
                        'serial10' => $value->serial10
                    ]);
            }
            else {
                $store = DB::table('stores')
                    ->where('bea_mo_store_name',$value->customer_name)
                    ->where('status','ACTIVE')->first();
                $ebs_pull_id = DB::table('ebs_pull')->insertGetId(array_merge((array)$value,['stores_id'=>$store->id,'transaction_type'=>'MO-RMA','status'=>'PROCESSING','created_at'=>date('Y-m-d H:i:s'),'data_pull_date'=>date('Y-m-d')]));
                $getSerial = self::getSerialById($ebs_pull_id);
                $serials = explode(",",$getSerial->serial);
                foreach ($serials as $serial) {
                    if($serial != ""){
                        DB::table('serials')->insert([
                            'ebs_pull_id' => $ebs_pull_id, 
                            'serial_number' => $serial,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
                $customer_name = DB::table('stores')
                    ->where('bea_mo_store_name', $value->customer_name)
                    ->where('status','ACTIVE')
                    ->select('doo_subinventory')->first();
                //autocreate DOT 
                app(EBSPushController::class)->createDOT($value->dr_number, $value->ordered_item_id, $value->shipped_quantity, $customer_name->doo_subinventory, $value->locator_id, 225);
                $record = true;
            }
        }
        if($record){
            // app(EBSPushController::class)->processTransactionInterface();
            \Log::info('Move Order Pull RMA - Done');
        }
    }

    public function moveOrderPullRmaManual($datefrom, $dateto)
    {

        $request_numbers = array();

        $shipment_numbers = DB::connection('oracle')->table('MTL_MATERIAL_TRANSACTIONS')
            ->join('MTL_TXN_REQUEST_HEADERS','MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID','=','MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER')
            ->select('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID as SHIPMENT_NUMBER')
            ->whereBetween('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_DATE', [$datefrom.' 00:00:00', $dateto.' 23:59:59'])
            ->where('MTL_MATERIAL_TRANSACTIONS.ORGANIZATION_ID', 225)
            ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 225)
            ->where('MTL_MATERIAL_TRANSACTIONS.SUBINVENTORY_CODE', 'STAGINGMO')
            ->where('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_TYPE_ID', 64)
            ->distinct()->get();
            
        foreach ($shipment_numbers as $key => $value) {
            array_push($request_numbers, $value->shipment_number);
        }

        $data['move_order'] = DB::connection('oracle')->table('MTL_TXN_REQUEST_HEADERS')
            ->join('MTL_TXN_REQUEST_LINES','MTL_TXN_REQUEST_HEADERS.HEADER_ID','=','MTL_TXN_REQUEST_LINES.HEADER_ID')
            ->join('MTL_SYSTEM_ITEMS_B','MTL_TXN_REQUEST_LINES.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
            ->join('MTL_ITEM_LOCATIONS','MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID','=','MTL_ITEM_LOCATIONS.INVENTORY_LOCATION_ID')
            ->select(
                'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as ORDER_NUMBER',
                'MTL_TXN_REQUEST_LINES.LINE_NUMBER',
                'MTL_SYSTEM_ITEMS_B.SEGMENT1 as ORDERED_ITEM',
                'MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID as ORDERED_ITEM_ID',
                'MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED as SHIPPED_QUANTITY',
                'MTL_ITEM_LOCATIONS.SEGMENT2 as CUSTOMER_NAME',
                'MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID as LOCATOR_ID',
                'MTL_TXN_REQUEST_HEADERS.DESCRIPTION as SHIPPING_INSTRUCTION', 
                'MTL_TXN_REQUEST_LINES.REFERENCE as CUSTOMER_PO', 
                'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as DR_NUMBER',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE12 as SERIAL1',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE13 as SERIAL2',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE14 as SERIAL3',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE15 as SERIAL4',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE4 as SERIAL5',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE5 as SERIAL6',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE6 as SERIAL7',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE7 as SERIAL8',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE8 as SERIAL9',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE9 as SERIAL10'
            )
            ->where('MTL_TXN_REQUEST_HEADERS.MOVE_ORDER_TYPE', 1)
            ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 225)
            ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID', 223)
            ->whereNotNull('MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED')
            ->where('MTL_TXN_REQUEST_LINES.LINE_STATUS','!=', '6')
            ->whereIn('MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER', array_values($request_numbers))
            ->where(function($query) {
                $query->where(\DB::raw('substr(MTL_ITEM_LOCATIONS.SEGMENT2, -3)'), 'LIKE', 'RTL');
            })
            ->get();
            
        $record = false;
        foreach ($data['move_order'] as $key => $value) {
            $itemExists = DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)->where('dr_number',$value->dr_number)->first();
                
            if(!empty($itemExists)){
                DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)
                    ->where('dr_number',$value->dr_number)
                    ->update([
                        'serial1' => $value->serial1,
                        'serial2' => $value->serial2,
                        'serial3' => $value->serial3,
                        'serial4' => $value->serial4,
                        'serial5' => $value->serial5,
                        'serial6' => $value->serial6,
                        'serial7' => $value->serial7,
                        'serial8' => $value->serial8,
                        'serial9' => $value->serial9,
                        'serial10' => $value->serial10
                    ]);
            }
            else {
                $store = DB::table('stores')
                    ->where('bea_mo_store_name',$value->customer_name)
                    ->where('status','ACTIVE')->first();
                $ebs_pull_id = DB::table('ebs_pull')->insertGetId(array_merge((array)$value,['stores_id'=>$store->id,'transaction_type'=>'MO-RMA','status'=>'PROCESSING','created_at'=>date('Y-m-d H:i:s'),'data_pull_date'=>date('Y-m-d')]));
                $getSerial = self::getSerialById($ebs_pull_id);
                $serials = explode(",",$getSerial->serial);
                foreach ($serials as $serial) {
                    if($serial != ""){
                        DB::table('serials')->insert([
                            'ebs_pull_id' => $ebs_pull_id, 
                            'serial_number' => $serial,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
                $customer_name = DB::table('stores')
                    ->where('bea_mo_store_name', $value->customer_name)
                    ->where('status','ACTIVE')
                    ->select('doo_subinventory')->first();
                //autocreate DOT 
                app(EBSPushController::class)->createDOT($value->dr_number, $value->ordered_item_id, $value->shipped_quantity, $customer_name->doo_subinventory, $value->locator_id, 225);
                $record = true;
            }
        }
        if($record){
            // app(EBSPushController::class)->processTransactionInterface();
            \Log::info('Move Order Pull RMA - Done');
        }
    }
    
    public function moveOrderPullManual($datefrom, $dateto)
    {

        $request_numbers = array();
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2G');

        $shipment_numbers = DB::connection('oracle')->table('MTL_MATERIAL_TRANSACTIONS')
            ->join('MTL_TXN_REQUEST_HEADERS','MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID','=','MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER')
            ->select('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID as SHIPMENT_NUMBER')
            ->whereBetween('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_DATE', [$datefrom.' 00:00:00', $dateto.' 23:59:59'])
            // ->whereBetween('MTL_TXN_REQUEST_HEADERS.CREATION_DATE', [$datefrom, $dateto])
            ->where('MTL_MATERIAL_TRANSACTIONS.ORGANIZATION_ID', 224)
            ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 224)
            ->where('MTL_MATERIAL_TRANSACTIONS.SUBINVENTORY_CODE', 'STAGINGMO')
            ->where('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_TYPE_ID', 64)
            ->distinct()->get();
        
        foreach ($shipment_numbers as $key => $value) {
            array_push($request_numbers, $value->shipment_number);
        }

        $data['move_order'] = DB::connection('oracle')->table('MTL_TXN_REQUEST_HEADERS')
            ->join('MTL_TXN_REQUEST_LINES','MTL_TXN_REQUEST_HEADERS.HEADER_ID','=','MTL_TXN_REQUEST_LINES.HEADER_ID')
            ->join('MTL_SYSTEM_ITEMS_B','MTL_TXN_REQUEST_LINES.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
            //->join('RCV_SHIPMENT_HEADERS','MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER','=','RCV_SHIPMENT_HEADERS.SHIPMENT_NUM') //removed 2020-12-03
            ->join('MTL_ITEM_LOCATIONS','MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID','=','MTL_ITEM_LOCATIONS.INVENTORY_LOCATION_ID')
            ->select(
                'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as ORDER_NUMBER',
                'MTL_TXN_REQUEST_LINES.LINE_NUMBER',
                'MTL_SYSTEM_ITEMS_B.SEGMENT1 as ORDERED_ITEM',
                'MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID as ORDERED_ITEM_ID',
                'MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED as SHIPPED_QUANTITY',
                'MTL_ITEM_LOCATIONS.SEGMENT2 as CUSTOMER_NAME',
                'MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID as LOCATOR_ID',
                //'RCV_SHIPMENT_HEADERS.SHIPPED_DATE as SHIP_CONFIRMED_DATE', //removed 2020-12-03
                'MTL_TXN_REQUEST_HEADERS.DESCRIPTION as SHIPPING_INSTRUCTION', //added 2021-03-05
                'MTL_TXN_REQUEST_LINES.REFERENCE as CUSTOMER_PO', //added 2022-05-02
                'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as DR_NUMBER',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE12 as SERIAL1',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE13 as SERIAL2',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE14 as SERIAL3',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE15 as SERIAL4',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE4 as SERIAL5',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE5 as SERIAL6',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE6 as SERIAL7',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE7 as SERIAL8',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE8 as SERIAL9',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE9 as SERIAL10'
            )
            ->where('MTL_TXN_REQUEST_HEADERS.MOVE_ORDER_TYPE', 1)
            ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 224)
            ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID', 223)
            ->whereNotNull('MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED')
            ->where('MTL_TXN_REQUEST_LINES.LINE_STATUS','!=', '6')
            // ->where('MTL_ITEM_LOCATIONS.SEGMENT2','CLEARANCE DW ROB ANTIPOLO 2022 RTL')
            ->whereIn('MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER', array_values($request_numbers)) //
            ->where(function($query) {
                $query->where(\DB::raw('substr(MTL_ITEM_LOCATIONS.SEGMENT2, -3)'), '=', 'RTL')
                ->orWhere(\DB::raw('substr(MTL_ITEM_LOCATIONS.SEGMENT2, -3)'), '=', 'FRA');
            })
            ->get();
        
        $record = false;
        foreach ($data['move_order'] as $key => $value) {
            $itemExists = DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)->where('dr_number',$value->dr_number)->first();
                
            if(!empty($itemExists)){
                DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)
                    ->where('dr_number',$value->dr_number)
                    ->update([
                        'serial1' => $value->serial1,
                        'serial2' => $value->serial2,
                        'serial3' => $value->serial3,
                        'serial4' => $value->serial4,
                        'serial5' => $value->serial5,
                        'serial6' => $value->serial6,
                        'serial7' => $value->serial7,
                        'serial8' => $value->serial8,
                        'serial9' => $value->serial9,
                        'serial10' => $value->serial10
                    ]);
            }
            else {
                $store = DB::table('stores')
                    ->where('bea_mo_store_name',$value->customer_name)
                    ->where('status','ACTIVE')->first();
                $ebs_pull_id = DB::table('ebs_pull')->insertGetId(array_merge((array)$value,['stores_id'=>$store->id,'transaction_type'=>'MO','status'=>'PROCESSING','created_at'=>date('Y-m-d H:i:s'),'data_pull_date'=>date('Y-m-d')]));
                $getSerial = self::getSerialById($ebs_pull_id);
                $serials = explode(",",$getSerial->serial);
                foreach ($serials as $serial) {
                    if($serial != ""){
                        DB::table('serials')->insert([
                            'ebs_pull_id' => $ebs_pull_id, 
                            'serial_number' => $serial,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
                $customer_name = DB::table('stores')
                    ->where('bea_mo_store_name', $value->customer_name)
                    ->where('status','ACTIVE')
                    ->select('doo_subinventory')->first();
                //autocreate DOT 
                app(EBSPushController::class)->createDOT($value->dr_number, $value->ordered_item_id, $value->shipped_quantity, $customer_name->doo_subinventory, $value->locator_id);
                $record = true;
            }
        }
        if($record){
            // app(EBSPushController::class)->processTransactionInterface();
            \Log::info('Move Order Pull - Done');
        }
    }
    
    public function moveOrderPullManualTime(Request $request)
    {
        
        $request_numbers = array(); 

        $shipment_numbers = DB::connection('oracle')->table('MTL_MATERIAL_TRANSACTIONS')
            ->join('MTL_TXN_REQUEST_HEADERS','MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID','=','MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER')
            ->select('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID as SHIPMENT_NUMBER')
            ->whereBetween('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_DATE', [$request->datefrom, $request->dateto])
            ->where('MTL_MATERIAL_TRANSACTIONS.ORGANIZATION_ID', 224)
            ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 224)
            ->where('MTL_MATERIAL_TRANSACTIONS.SUBINVENTORY_CODE', 'STAGINGMO')
            ->where('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_TYPE_ID', 64)
            ->distinct()->get();
        
        
        foreach ($shipment_numbers as $key => $value) {
            array_push($request_numbers, $value->shipment_number);
        }

        $data['move_order'] = DB::connection('oracle')->table('MTL_TXN_REQUEST_HEADERS')
            ->join('MTL_TXN_REQUEST_LINES','MTL_TXN_REQUEST_HEADERS.HEADER_ID','=','MTL_TXN_REQUEST_LINES.HEADER_ID')
            ->join('MTL_SYSTEM_ITEMS_B','MTL_TXN_REQUEST_LINES.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
            ->join('MTL_ITEM_LOCATIONS','MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID','=','MTL_ITEM_LOCATIONS.INVENTORY_LOCATION_ID')
            ->select(
                'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as ORDER_NUMBER',
                'MTL_TXN_REQUEST_LINES.LINE_NUMBER',
                'MTL_SYSTEM_ITEMS_B.SEGMENT1 as ORDERED_ITEM',
                'MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID as ORDERED_ITEM_ID',
                'MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED as SHIPPED_QUANTITY',
                'MTL_ITEM_LOCATIONS.SEGMENT2 as CUSTOMER_NAME',
                'MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID as LOCATOR_ID',
                'MTL_TXN_REQUEST_HEADERS.DESCRIPTION as SHIPPING_INSTRUCTION', //added 2021-03-05
                'MTL_TXN_REQUEST_LINES.REFERENCE as CUSTOMER_PO', //added 2022-05-02
                'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as DR_NUMBER',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE12 as SERIAL1',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE13 as SERIAL2',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE14 as SERIAL3',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE15 as SERIAL4',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE4 as SERIAL5',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE5 as SERIAL6',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE6 as SERIAL7',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE7 as SERIAL8',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE8 as SERIAL9',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE9 as SERIAL10'
            )
            ->where('MTL_TXN_REQUEST_HEADERS.MOVE_ORDER_TYPE', 1)
            ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 224)
            ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID', 223)
            ->whereNotNull('MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED')
            ->where('MTL_TXN_REQUEST_LINES.LINE_STATUS','!=', '6')
            ->whereIn('MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER', array_values($request_numbers))
            ->where(function($query) {
                $query->where(\DB::raw('substr(MTL_ITEM_LOCATIONS.SEGMENT2, -3)'), '=', 'RTL')
                ->orWhere(\DB::raw('substr(MTL_ITEM_LOCATIONS.SEGMENT2, -3)'), '=', 'FRA');
            })
            ->get();
        
        $record = false;
        foreach ($data['move_order'] as $key => $value) {
            $itemExists = DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)->where('dr_number',$value->dr_number)->first();
               
            if(!empty($itemExists)){
                DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)
                    ->where('dr_number',$value->dr_number)
                    ->update([
                        'serial1' => $value->serial1,
                        'serial2' => $value->serial2,
                        'serial3' => $value->serial3,
                        'serial4' => $value->serial4,
                        'serial5' => $value->serial5,
                        'serial6' => $value->serial6,
                        'serial7' => $value->serial7,
                        'serial8' => $value->serial8,
                        'serial9' => $value->serial9,
                        'serial10' => $value->serial10
                    ]);
                    $record = true;
            }
            else {
                $store = DB::table('stores')
                    ->where('bea_mo_store_name',$value->customer_name)
                    ->where('status','ACTIVE')
                    ->first();
                $ebs_pull_id = DB::table('ebs_pull')->insertGetId(array_merge((array)$value,['stores_id'=>$store->id,'transaction_type'=>'MO','status'=>'PROCESSING','created_at'=>date('Y-m-d H:i:s'),'data_pull_date'=>date('Y-m-d')]));
                $getSerial = self::getSerialById($ebs_pull_id);
                $serials = explode(",",$getSerial->serial);
                foreach ($serials as $serial) {
                    if($serial != ""){
                        DB::table('serials')->insert([
                            'ebs_pull_id' => $ebs_pull_id, 
                            'serial_number' => $serial,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
                $customer_name = DB::table('stores')
                    ->where('bea_mo_store_name', $value->customer_name)
                    ->where('status','ACTIVE')
                    ->select('doo_subinventory')->first();
                //autocreate DOT 
                app(EBSPushController::class)->createDOT($value->dr_number, $value->ordered_item_id, $value->shipped_quantity, $customer_name->doo_subinventory, $value->locator_id);
                $record = true;
            }
        }
        if($record){
             
            // app(EBSPushController::class)->processTransactionInterface();
            \Log::info('Move Order Pull with Time - Done');
            return response()->json(['success' => 'true', 'message' => 'Move Order Pull with Time - Done']);
            // return json_encode(array('success' => true));
        }
    }

    public function moveOrderPullDistri() //$datefrom, $dateto
    {
        $datefrom = date("Y-m-d H:i:s", strtotime("-5 hour"));
        $dateto = date("Y-m-d H:i:s", strtotime("-1 hour"));
        
        $request_numbers = array();

        $shipment_numbers = DB::connection('oracle')->table('MTL_MATERIAL_TRANSACTIONS')
            ->join('MTL_TXN_REQUEST_HEADERS','MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID','=','MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER')
            ->select('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID as SHIPMENT_NUMBER')
            ->whereBetween('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_DATE', [$datefrom, $dateto])
            // ->whereBetween('MTL_TXN_REQUEST_HEADERS.CREATION_DATE', [$datefrom, $dateto])
            ->where('MTL_MATERIAL_TRANSACTIONS.ORGANIZATION_ID', 224)
            ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 224)
            ->where('MTL_MATERIAL_TRANSACTIONS.SUBINVENTORY_CODE', 'STAGINGMO')
            ->where('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_TYPE_ID', 64)
            ->distinct()->get();
        
        foreach ($shipment_numbers as $key => $value) {
            array_push($request_numbers, $value->shipment_number);
        }

        $data['move_order'] = DB::connection('oracle')->table('MTL_TXN_REQUEST_HEADERS')
            ->join('MTL_TXN_REQUEST_LINES','MTL_TXN_REQUEST_HEADERS.HEADER_ID','=','MTL_TXN_REQUEST_LINES.HEADER_ID')
            ->join('MTL_SYSTEM_ITEMS_B','MTL_TXN_REQUEST_LINES.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
            ->join('MTL_ITEM_LOCATIONS','MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID','=','MTL_ITEM_LOCATIONS.INVENTORY_LOCATION_ID')
            ->select(
                'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as ORDER_NUMBER',
                'MTL_TXN_REQUEST_LINES.LINE_NUMBER',
                'MTL_SYSTEM_ITEMS_B.SEGMENT1 as ORDERED_ITEM',
                'MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID as ORDERED_ITEM_ID',
                'MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED as SHIPPED_QUANTITY',
                'MTL_ITEM_LOCATIONS.SEGMENT2 as CUSTOMER_NAME',
                'MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID as LOCATOR_ID',
                'MTL_TXN_REQUEST_HEADERS.DESCRIPTION as SHIPPING_INSTRUCTION', //added 2021-03-05
                'MTL_TXN_REQUEST_LINES.REFERENCE as CUSTOMER_PO', //added 2022-05-02
                'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as DR_NUMBER',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE12 as SERIAL1',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE13 as SERIAL2',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE14 as SERIAL3',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE15 as SERIAL4',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE4 as SERIAL5',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE5 as SERIAL6',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE6 as SERIAL7',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE7 as SERIAL8',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE8 as SERIAL9',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE9 as SERIAL10'
            )
            ->where('MTL_TXN_REQUEST_HEADERS.MOVE_ORDER_TYPE', 1)
            ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 224)
            ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID', 223)
            ->where('MTL_TXN_REQUEST_LINES.LINE_STATUS','!=', '6')
            ->whereIn('MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER', array_values($request_numbers))
            ->where(function($query) {
                $query->where(\DB::raw('substr(MTL_ITEM_LOCATIONS.SEGMENT2, -3)'), '=', 'SLE')
                ->orWhere(\DB::raw('substr(MTL_ITEM_LOCATIONS.SEGMENT2, -3)'), '=', 'CON');
            })
            ->get();
        
        $record = false;
        foreach ($data['move_order'] as $key => $value) {
            $itemExists = DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)->where('dr_number',$value->dr_number)->first();
                
            if(!empty($itemExists)){
                DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)
                    ->where('dr_number',$value->dr_number)
                    ->update([
                        'serial1' => $value->serial1,
                        'serial2' => $value->serial2,
                        'serial3' => $value->serial3,
                        'serial4' => $value->serial4,
                        'serial5' => $value->serial5,
                        'serial6' => $value->serial6,
                        'serial7' => $value->serial7,
                        'serial8' => $value->serial8,
                        'serial9' => $value->serial9,
                        'serial10' => $value->serial10
                    ]);
            }
            else {
                $store = DB::table('stores')
                    ->where('bea_mo_store_name',$value->customer_name)
                    ->where('status','ACTIVE')->first();
                $ebs_pull_id = DB::table('ebs_pull')->insertGetId(array_merge((array)$value,['stores_id'=>$store->id,'transaction_type'=>'MO','status'=>'PROCESSING','created_at'=>date('Y-m-d H:i:s'),'data_pull_date'=>date('Y-m-d')]));
                $getSerial = self::getSerialById($ebs_pull_id);
                $serials = explode(",",$getSerial->serial);
                foreach ($serials as $serial) {
                    if($serial != ""){
                        DB::table('serials')->insert([
                            'ebs_pull_id' => $ebs_pull_id, 
                            'serial_number' => $serial,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
                $customer_name = DB::table('stores')
                    ->where('bea_mo_store_name', $value->customer_name)
                    ->where('status','ACTIVE')->select('doo_subinventory')->first();
                //autocreate DOT 
                app(EBSPushController::class)->createDOT($value->dr_number, $value->ordered_item_id, $value->shipped_quantity, $customer_name->doo_subinventory, $value->locator_id);
                $record = true;
            }
        }
        // if($record){
        //     app(EBSPushController::class)->processTransactionInterface();
        // }
    }
    
    public function moveOrderPullDistriManual($datefrom, $dateto) 
    {
        
        $request_numbers = array();

        $shipment_numbers = DB::connection('oracle')->table('MTL_MATERIAL_TRANSACTIONS')
            ->join('MTL_TXN_REQUEST_HEADERS','MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID','=','MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER')
            ->select('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID as SHIPMENT_NUMBER')
            ->whereBetween('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_DATE', [$datefrom.' 00:00:00', $dateto.' 23:23:59'])
            // ->whereBetween('MTL_TXN_REQUEST_HEADERS.CREATION_DATE', [$datefrom, $dateto])
            ->where('MTL_MATERIAL_TRANSACTIONS.ORGANIZATION_ID', 224)
            ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 224)
            ->where('MTL_MATERIAL_TRANSACTIONS.SUBINVENTORY_CODE', 'STAGINGMO')
            ->where('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_TYPE_ID', 64)
            ->distinct()->get();
        
        foreach ($shipment_numbers as $key => $value) {
            array_push($request_numbers, $value->shipment_number);
        }

        $data['move_order'] = DB::connection('oracle')->table('MTL_TXN_REQUEST_HEADERS')
            ->join('MTL_TXN_REQUEST_LINES','MTL_TXN_REQUEST_HEADERS.HEADER_ID','=','MTL_TXN_REQUEST_LINES.HEADER_ID')
            ->join('MTL_SYSTEM_ITEMS_B','MTL_TXN_REQUEST_LINES.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
            ->join('MTL_ITEM_LOCATIONS','MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID','=','MTL_ITEM_LOCATIONS.INVENTORY_LOCATION_ID')
            ->select(
                'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as ORDER_NUMBER',
                'MTL_TXN_REQUEST_LINES.LINE_NUMBER',
                'MTL_SYSTEM_ITEMS_B.SEGMENT1 as ORDERED_ITEM',
                'MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID as ORDERED_ITEM_ID',
                'MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED as SHIPPED_QUANTITY',
                'MTL_ITEM_LOCATIONS.SEGMENT2 as CUSTOMER_NAME',
                'MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID as LOCATOR_ID',
                'MTL_TXN_REQUEST_HEADERS.DESCRIPTION as SHIPPING_INSTRUCTION', //added 2021-03-05
                'MTL_TXN_REQUEST_LINES.REFERENCE as CUSTOMER_PO', //added 2022-05-02
                'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as DR_NUMBER',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE12 as SERIAL1',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE13 as SERIAL2',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE14 as SERIAL3',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE15 as SERIAL4',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE4 as SERIAL5',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE5 as SERIAL6',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE6 as SERIAL7',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE7 as SERIAL8',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE8 as SERIAL9',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE9 as SERIAL10'
            )
            ->where('MTL_TXN_REQUEST_HEADERS.MOVE_ORDER_TYPE', 1)
            ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 224)
            ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID', 223)
            ->where('MTL_TXN_REQUEST_LINES.LINE_STATUS','!=', '6')
            ->whereIn('MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER', array_values($request_numbers))
            ->where(function($query) {
                $query->where(\DB::raw('substr(MTL_ITEM_LOCATIONS.SEGMENT2, -3)'), '=', 'SLE')
                ->orWhere(\DB::raw('substr(MTL_ITEM_LOCATIONS.SEGMENT2, -3)'), '=', 'CON');
            })
            ->get();
        
        $record = false;
        foreach ($data['move_order'] as $key => $value) {
            $itemExists = DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)->where('dr_number',$value->dr_number)->first();
                
            if(!empty($itemExists)){
                DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)
                    ->where('dr_number',$value->dr_number)
                    ->update([
                        'serial1' => $value->serial1,
                        'serial2' => $value->serial2,
                        'serial3' => $value->serial3,
                        'serial4' => $value->serial4,
                        'serial5' => $value->serial5,
                        'serial6' => $value->serial6,
                        'serial7' => $value->serial7,
                        'serial8' => $value->serial8,
                        'serial9' => $value->serial9,
                        'serial10' => $value->serial10
                    ]);
            }
            else {
                $store = DB::table('stores')
                    ->where('bea_mo_store_name',$value->customer_name)
                    ->where('status','ACTIVE')->first();
                $ebs_pull_id = DB::table('ebs_pull')->insertGetId(array_merge((array)$value,['stores_id'=>$store->id,'transaction_type'=>'MO','status'=>'PROCESSING','created_at'=>date('Y-m-d H:i:s'),'data_pull_date'=>date('Y-m-d')]));
                $getSerial = self::getSerialById($ebs_pull_id);
                $serials = explode(",",$getSerial->serial);
                foreach ($serials as $serial) {
                    if($serial != ""){
                        DB::table('serials')->insert([
                            'ebs_pull_id' => $ebs_pull_id, 
                            'serial_number' => $serial,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
                $customer_name = DB::table('stores')
                    ->where('bea_mo_store_name', $value->customer_name)
                    ->where('status','ACTIVE')
                    ->select('doo_subinventory')->first();
                //autocreate DOT 
                app(EBSPushController::class)->createDOT($value->dr_number, $value->ordered_item_id, $value->shipped_quantity, $customer_name->doo_subinventory, $value->locator_id);
                $record = true;
            }
        }
        // if($record){
        //     app(EBSPushController::class)->processTransactionInterface();
        // }
    }

    public function moveOrderPullOnlineOld() //$datefrom, $dateto
    {
        $datefrom = date("Y-m-d H:i:s", strtotime("-5 hour")); 
        $dateto = date("Y-m-d H:i:s", strtotime("-1 hour"));
        
        $request_numbers = array();

        $shipment_numbers = DB::connection('oracle')->table('MTL_MATERIAL_TRANSACTIONS')
            ->join('MTL_TXN_REQUEST_HEADERS','MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID','=','MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER')
            ->select('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID as SHIPMENT_NUMBER')
            ->whereBetween('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_DATE', [$datefrom, $dateto])
            //->whereBetween('MTL_TXN_REQUEST_HEADERS.CREATION_DATE', [$datefrom, $dateto])
            ->where('MTL_MATERIAL_TRANSACTIONS.ORGANIZATION_ID', 263) //deo org update in prod
            ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 263)
            ->where('MTL_MATERIAL_TRANSACTIONS.SUBINVENTORY_CODE', 'STAGINGMO')
            ->where('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_TYPE_ID', 64)
            ->distinct()->get();
        
        foreach ($shipment_numbers as $key => $value) {
            array_push($request_numbers, $value->shipment_number);
        }

        $data['move_order'] = OracleTransactionHeader::getMoveOrders($request_numbers,'DEO')->get();

        // $data['move_order'] = DB::connection('oracle')->table('MTL_TXN_REQUEST_HEADERS')
        //     ->join('MTL_TXN_REQUEST_LINES','MTL_TXN_REQUEST_HEADERS.HEADER_ID','=','MTL_TXN_REQUEST_LINES.HEADER_ID')
        //     ->join('MTL_SYSTEM_ITEMS_B','MTL_TXN_REQUEST_LINES.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
        //     ->join('MTL_ITEM_LOCATIONS','MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID','=','MTL_ITEM_LOCATIONS.INVENTORY_LOCATION_ID')
        //     ->select(
        //         'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as ORDER_NUMBER',
        //         'MTL_TXN_REQUEST_LINES.LINE_NUMBER',
        //         'MTL_SYSTEM_ITEMS_B.SEGMENT1 as ORDERED_ITEM',
        //         'MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID as ORDERED_ITEM_ID',
        //         'MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED as SHIPPED_QUANTITY',
        //         'MTL_ITEM_LOCATIONS.SEGMENT2 as CUSTOMER_NAME',
        //         'MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID as LOCATOR_ID',
        //         'MTL_TXN_REQUEST_HEADERS.DESCRIPTION as SHIPPING_INSTRUCTION', //added 2021-03-05
        //         'MTL_TXN_REQUEST_LINES.REFERENCE as CUSTOMER_PO', //added 2022-05-02
        //         'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as DR_NUMBER',
        //         'MTL_TXN_REQUEST_LINES.ATTRIBUTE12 as SERIAL1',
        //         'MTL_TXN_REQUEST_LINES.ATTRIBUTE13 as SERIAL2',
        //         'MTL_TXN_REQUEST_LINES.ATTRIBUTE14 as SERIAL3',
        //         'MTL_TXN_REQUEST_LINES.ATTRIBUTE15 as SERIAL4',
        //         'MTL_TXN_REQUEST_LINES.ATTRIBUTE4 as SERIAL5',
        //         'MTL_TXN_REQUEST_LINES.ATTRIBUTE5 as SERIAL6',
        //         'MTL_TXN_REQUEST_LINES.ATTRIBUTE6 as SERIAL7',
        //         'MTL_TXN_REQUEST_LINES.ATTRIBUTE7 as SERIAL8',
        //         'MTL_TXN_REQUEST_LINES.ATTRIBUTE8 as SERIAL9',
        //         'MTL_TXN_REQUEST_LINES.ATTRIBUTE9 as SERIAL10'
        //     )
        //     ->where('MTL_TXN_REQUEST_HEADERS.MOVE_ORDER_TYPE', 1)
        //     ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 263) //deo org update in prod
        //     ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID', 223)
        //     ->where('MTL_TXN_REQUEST_LINES.LINE_STATUS','!=', '6')
        //     ->whereIn('MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER', array_values($request_numbers))
        //     ->get();
        
        $record = false;
        foreach ($data['move_order'] as $key => $value) {
            $itemExists = DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)->where('dr_number',$value->dr_number)->first();
                
            if(!empty($itemExists)){
                DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)
                    ->where('dr_number',$value->dr_number)
                    ->update([
                        'serial1' => $value->serial1,
                        'serial2' => $value->serial2,
                        'serial3' => $value->serial3,
                        'serial4' => $value->serial4,
                        'serial5' => $value->serial5,
                        'serial6' => $value->serial6,
                        'serial7' => $value->serial7,
                        'serial8' => $value->serial8,
                        'serial9' => $value->serial9,
                        'serial10' => $value->serial10
                    ]);
            }
            else {
                $store = DB::table('stores')
                    ->where('bea_mo_store_name',$value->customer_name)
                    ->where('status','ACTIVE')->first();
                $ebs_pull_id = DB::table('ebs_pull')->insertGetId(array_merge((array)$value,['stores_id'=>$store->id,'transaction_type'=>'MO','status'=>(substr($value->customer_name, -3) == 'FBD') ? 'PENDING' : 'PROCESSING','created_at'=>date('Y-m-d H:i:s'),'data_pull_date'=>date('Y-m-d')]));
                $getSerial = self::getSerialById($ebs_pull_id);
                $serials = explode(",",$getSerial->serial);
                foreach ($serials as $serial) {
                    if($serial != ""){
                        DB::table('serials')->insert([
                            'ebs_pull_id' => $ebs_pull_id, 
                            'serial_number' => $serial,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
                $customer_name = DB::table('stores')
                    ->where('bea_mo_store_name', $value->customer_name)
                    ->where('status','ACTIVE')
                    ->select('doo_subinventory','sit_subinventory')->first();
                //autocreate DOT 
                // if(substr($value->customer_name, -3) == 'FBD'){
                //     app(EBSPushController::class)->createSIT($value->dr_number, $value->ordered_item_id, $value->shipped_quantity, 'STAGINGMO', $customer_name->sit_subinventory, $value->locator_id);
                // }
                if(substr($value->customer_name, -3) == 'FBV'){
                    app(EBSPushController::class)->createDOT($value->dr_number, $value->ordered_item_id, $value->shipped_quantity, $customer_name->doo_subinventory, $value->locator_id, 263);
                }
                $record = true;
            }
        }
        if($record){
            \Log::info('Move Order Pull Online - Done');
        }
    }
    
    public function moveOrderPullOnline() //$datefrom, $dateto
    {
        $datefrom = date("Y-m-d H:i:s", strtotime("-5 hour"));
        $dateto = date("Y-m-d H:i:s", strtotime("-1 hour"));

        $request_numbers = array();

        $shipment_numbers = DB::connection('oracle')->table('MTL_MATERIAL_TRANSACTIONS')
            ->join('MTL_TXN_REQUEST_HEADERS','MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID','=','MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER')
            ->select('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID as SHIPMENT_NUMBER')
            ->whereBetween('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_DATE', [$datefrom, $dateto])
            // ->whereBetween('MTL_TXN_REQUEST_HEADERS.CREATION_DATE', [$datefrom, $dateto])
            ->where('MTL_MATERIAL_TRANSACTIONS.ORGANIZATION_ID', 263)
            ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 263)
            ->where('MTL_MATERIAL_TRANSACTIONS.SUBINVENTORY_CODE', 'STAGINGMO')
            ->where('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_TYPE_ID', 64)
            ->distinct()->get();
        
        foreach ($shipment_numbers as $key => $value) {
            array_push($request_numbers, $value->shipment_number);
        }

        $data['move_order'] = DB::connection('oracle')->table('MTL_TXN_REQUEST_HEADERS')
            ->join('MTL_TXN_REQUEST_LINES','MTL_TXN_REQUEST_HEADERS.HEADER_ID','=','MTL_TXN_REQUEST_LINES.HEADER_ID')
            ->join('MTL_SYSTEM_ITEMS_B','MTL_TXN_REQUEST_LINES.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
            //->join('RCV_SHIPMENT_HEADERS','MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER','=','RCV_SHIPMENT_HEADERS.SHIPMENT_NUM') //removed 2020-12-03
            ->join('MTL_ITEM_LOCATIONS','MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID','=','MTL_ITEM_LOCATIONS.INVENTORY_LOCATION_ID')
            ->select(
                'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as ORDER_NUMBER',
                'MTL_TXN_REQUEST_LINES.LINE_NUMBER',
                'MTL_SYSTEM_ITEMS_B.SEGMENT1 as ORDERED_ITEM',
                'MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID as ORDERED_ITEM_ID',
                'MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED as SHIPPED_QUANTITY',
                'MTL_ITEM_LOCATIONS.SEGMENT2 as CUSTOMER_NAME',
                'MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID as LOCATOR_ID',
                //'RCV_SHIPMENT_HEADERS.SHIPPED_DATE as SHIP_CONFIRMED_DATE', //removed 2020-12-03
                'MTL_TXN_REQUEST_HEADERS.DESCRIPTION as SHIPPING_INSTRUCTION', //added 2021-03-05
                'MTL_TXN_REQUEST_LINES.REFERENCE as CUSTOMER_PO', //added 2022-05-02
                'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as DR_NUMBER',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE12 as SERIAL1',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE13 as SERIAL2',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE14 as SERIAL3',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE15 as SERIAL4',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE4 as SERIAL5',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE5 as SERIAL6',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE6 as SERIAL7',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE7 as SERIAL8',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE8 as SERIAL9',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE9 as SERIAL10'
            )
            ->where('MTL_TXN_REQUEST_HEADERS.MOVE_ORDER_TYPE', 1)
            ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 263)
            ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID', 223)
            ->whereNotNull('MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED')
            ->where('MTL_TXN_REQUEST_LINES.LINE_STATUS','!=', '6')
            ->whereIn('MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER', array_values($request_numbers))
            ->get();
        
        $record = false;
        foreach ($data['move_order'] as $key => $value) {
            $itemExists = DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)->where('dr_number',$value->dr_number)->first();
                
            if(!empty($itemExists)){
                DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)
                    ->where('dr_number',$value->dr_number)
                    ->update([
                        'serial1' => $value->serial1,
                        'serial2' => $value->serial2,
                        'serial3' => $value->serial3,
                        'serial4' => $value->serial4,
                        'serial5' => $value->serial5,
                        'serial6' => $value->serial6,
                        'serial7' => $value->serial7,
                        'serial8' => $value->serial8,
                        'serial9' => $value->serial9,
                        'serial10' => $value->serial10
                    ]);
            }
            else {
                $store = DB::table('stores')
                    ->where('bea_mo_store_name',$value->customer_name)
                    ->where('status','ACTIVE')->first();
                $ebs_pull_id = DB::table('ebs_pull')->insertGetId(array_merge((array)$value,['stores_id'=>$store->id,'transaction_type'=>'MO','status'=>(substr($value->customer_name, -3) == 'FBD') ? 'PENDING' : 'PROCESSING','created_at'=>date('Y-m-d H:i:s'),'data_pull_date'=>date('Y-m-d')]));
                $getSerial = self::getSerialById($ebs_pull_id);
                $serials = explode(",",$getSerial->serial);
                foreach ($serials as $serial) {
                    if($serial != ""){
                        DB::table('serials')->insert([
                            'ebs_pull_id' => $ebs_pull_id, 
                            'serial_number' => $serial,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
                $customer_name = DB::table('stores')
                    ->where('bea_mo_store_name', $value->customer_name)
                    ->where('status','ACTIVE')
                    ->select('doo_subinventory','sit_subinventory')->first();
                //autocreate DOT 
                // if(substr($value->customer_name, -3) == 'FBD'){
                //     app(EBSPushController::class)->createSIT($value->dr_number, $value->ordered_item_id, $value->shipped_quantity, 'STAGINGMO', $customer_name->sit_subinventory, $value->locator_id);
                // }
                if(substr($value->customer_name, -3) == 'FBV'){
                    app(EBSPushController::class)->createDOT($value->dr_number, $value->ordered_item_id, $value->shipped_quantity, $customer_name->doo_subinventory, $value->locator_id, 263);
                }
                $record = true;
            }
        }
        if($record){
            // app(EBSPushController::class)->processTransactionInterface();
            \Log::info('Move Order Pull Online - Done');
        }
    }
    
    public function moveOrderPullOnlManual($datefrom, $dateto) //$datefrom, $dateto 
    {
        
        $request_numbers = array();

        $shipment_numbers = DB::connection('oracle')->table('MTL_MATERIAL_TRANSACTIONS')
            ->join('MTL_TXN_REQUEST_HEADERS','MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID','=','MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER')
            ->select('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_SOURCE_ID as SHIPMENT_NUMBER')
            ->whereBetween('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_DATE', [$datefrom.' 00:00:00', $dateto.' 23:59:59'])
            // ->whereBetween('MTL_TXN_REQUEST_HEADERS.CREATION_DATE', [$datefrom, $dateto])
            ->where('MTL_MATERIAL_TRANSACTIONS.ORGANIZATION_ID', 263)
            ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 263)
            ->where('MTL_MATERIAL_TRANSACTIONS.SUBINVENTORY_CODE', 'STAGINGMO')
            ->where('MTL_MATERIAL_TRANSACTIONS.TRANSACTION_TYPE_ID', 64)
            ->distinct()->get();
        
        foreach ($shipment_numbers as $key => $value) {
            array_push($request_numbers, $value->shipment_number);
        }

        $data['move_order'] = DB::connection('oracle')->table('MTL_TXN_REQUEST_HEADERS')
            ->join('MTL_TXN_REQUEST_LINES','MTL_TXN_REQUEST_HEADERS.HEADER_ID','=','MTL_TXN_REQUEST_LINES.HEADER_ID')
            ->join('MTL_SYSTEM_ITEMS_B','MTL_TXN_REQUEST_LINES.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
            //->join('RCV_SHIPMENT_HEADERS','MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER','=','RCV_SHIPMENT_HEADERS.SHIPMENT_NUM') //removed 2020-12-03
            ->join('MTL_ITEM_LOCATIONS','MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID','=','MTL_ITEM_LOCATIONS.INVENTORY_LOCATION_ID')
            ->select(
                'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as ORDER_NUMBER',
                'MTL_TXN_REQUEST_LINES.LINE_NUMBER',
                'MTL_SYSTEM_ITEMS_B.SEGMENT1 as ORDERED_ITEM',
                'MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID as ORDERED_ITEM_ID',
                'MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED as SHIPPED_QUANTITY',
                'MTL_ITEM_LOCATIONS.SEGMENT2 as CUSTOMER_NAME',
                'MTL_TXN_REQUEST_LINES.TO_LOCATOR_ID as LOCATOR_ID',
                //'RCV_SHIPMENT_HEADERS.SHIPPED_DATE as SHIP_CONFIRMED_DATE', //removed 2020-12-03
                'MTL_TXN_REQUEST_HEADERS.DESCRIPTION as SHIPPING_INSTRUCTION', //added 2021-03-05
                'MTL_TXN_REQUEST_LINES.REFERENCE as CUSTOMER_PO', //added 2022-05-02
                'MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER as DR_NUMBER',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE12 as SERIAL1',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE13 as SERIAL2',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE14 as SERIAL3',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE15 as SERIAL4',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE4 as SERIAL5',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE5 as SERIAL6',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE6 as SERIAL7',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE7 as SERIAL8',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE8 as SERIAL9',
                'MTL_TXN_REQUEST_LINES.ATTRIBUTE9 as SERIAL10'
            )
            ->where('MTL_TXN_REQUEST_HEADERS.MOVE_ORDER_TYPE', 1)
            ->where('MTL_TXN_REQUEST_HEADERS.ORGANIZATION_ID', 263)
            ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID', 223)
            ->whereNotNull('MTL_TXN_REQUEST_LINES.QUANTITY_DELIVERED')
            ->where('MTL_TXN_REQUEST_LINES.LINE_STATUS','!=', '6')
            // ->where('MTL_ITEM_LOCATIONS.SEGMENT2','!=','LAZADA FITBIT FBV')
            ->whereIn('MTL_TXN_REQUEST_HEADERS.REQUEST_NUMBER', array_values($request_numbers))
            ->get();
        
        $record = false;
        foreach ($data['move_order'] as $key => $value) {
            $itemExists = DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)->where('dr_number',$value->dr_number)->first();
                
            if(!empty($itemExists)){
                DB::table('ebs_pull')->where('ordered_item',$value->ordered_item)
                    ->where('dr_number',$value->dr_number)
                    ->update([
                        'serial1' => $value->serial1,
                        'serial2' => $value->serial2,
                        'serial3' => $value->serial3,
                        'serial4' => $value->serial4,
                        'serial5' => $value->serial5,
                        'serial6' => $value->serial6,
                        'serial7' => $value->serial7,
                        'serial8' => $value->serial8,
                        'serial9' => $value->serial9,
                        'serial10' => $value->serial10
                    ]);
            }
            else {
                $store = DB::table('stores')
                    ->where('bea_mo_store_name',$value->customer_name)
                    ->where('status','ACTIVE')->first();
                $ebs_pull_id = DB::table('ebs_pull')->insertGetId(array_merge((array)$value,['stores_id'=>$store->id,'transaction_type'=>'MO','status'=>(substr($value->customer_name, -3) == 'FBD') ? 'PENDING' : 'PROCESSING','created_at'=>date('Y-m-d H:i:s'),'data_pull_date'=>date('Y-m-d')]));
                $getSerial = self::getSerialById($ebs_pull_id);
                $serials = explode(",",$getSerial->serial);
                foreach ($serials as $serial) {
                    if($serial != ""){
                        DB::table('serials')->insert([
                            'ebs_pull_id' => $ebs_pull_id, 
                            'serial_number' => $serial,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
                $customer_name = DB::table('stores')
                    ->where('bea_mo_store_name', $value->customer_name)
                    ->where('status','ACTIVE')
                    ->select('doo_subinventory','sit_subinventory')->first();
                //autocreate DOT 
                // if(substr($value->customer_name, -3) == 'FBD'){
                //     app(EBSPushController::class)->createSIT($value->dr_number, $value->ordered_item_id, $value->shipped_quantity, 'STAGINGMO', $customer_name->sit_subinventory, $value->locator_id);
                // }
                if(substr($value->customer_name, -3) == 'FBV'){
                    app(EBSPushController::class)->createDOT($value->dr_number, $value->ordered_item_id, $value->shipped_quantity, $customer_name->doo_subinventory, $value->locator_id, 263);
                }
                $record = true;
            }
        }
        if($record){
            // app(EBSPushController::class)->processTransactionInterface();
            \Log::info('Move Order Pull Online - Done');
        }
        
    }

    public function getDOTTransactions()
    {
        $deliveries = DB::table('ebs_pull')->where('status','PROCESSING')->select('order_number')->distinct()->get();
        foreach ($deliveries as $delivery) {
            $dot = DB::connection('oracle')->table('RCV_SHIPMENT_HEADERS')->where('SHIPMENT_NUM', $delivery->order_number)->first();
            // \Log::alert($dot);
            if(!empty($dot)){
                DB::table('ebs_pull')->where('order_number',$delivery->order_number)->update([
                    'status' => 'PENDING'
                ]);
            }
        }
        
    }

    public function getProductSerials($ordered_item, $dr_number)
    {
        return DB::table('ebs_pull')->where('ordered_item', $ordered_item)->where('dr_number', $dr_number)
            ->select(DB::raw('CONCAT_WS(",",serial1,serial2,serial3,serial4,serial5,serial6,serial7,serial8,serial9,serial10,serial11,serial12,serial13,serial14,serial15) as serial'),'shipped_quantity')->first();
    }
    
    public function getItemSerials($ordered_item, $dr_number)
    {
        $dr = DB::table('ebs_pull')->where('ordered_item', $ordered_item)->where('dr_number', $dr_number)->first();
        return DB::table('serials')->where('ebs_pull_id', $dr->id)->select('serial_number')->get()->toArray();
        
    }
    
    public function getDuplicateSerials($ordered_item, $dr_number)
    {
        $dr = DB::table('ebs_pull')->where('ordered_item', $ordered_item)->where('dr_number', $dr_number)->first();
        return DB::table('serials')->where('ebs_pull_id', $dr->id)
            ->select('serial_number',DB::raw("COUNT(*) duplicate"))
            ->groupBy('serial_number')
            ->havingRaw('duplicate > 1')
            ->get();
    }

    public function getSerialById($ebs_pull_id)
    {
        return DB::table('ebs_pull')->where('id',$ebs_pull_id)
            ->select(DB::raw('CONCAT_WS(",",serial1,serial2,serial3,serial4,serial5,serial6,serial7,serial8,serial9,serial10,serial11,serial12,serial13,serial14,serial15) as serial'))->first();
    }

    public function getProductsByDRNumber($dr_number)
    {
        return DB::table('ebs_pull')->where('dr_number', $dr_number)->select('ordered_item')->get();
    }

    public function getPriceList($customer_name)
    {

        return DB::connection('oracle')
            ->table('HZ_PARTIES')
            ->join('HZ_CUST_ACCOUNTS','HZ_PARTIES.PARTY_ID','=','HZ_CUST_ACCOUNTS.PARTY_ID')
            ->join('HZ_CUST_ACCT_SITES_ALL','HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID','=','HZ_CUST_ACCT_SITES_ALL.CUST_ACCOUNT_ID')
            ->join('HZ_CUST_SITE_USES_ALL','HZ_CUST_ACCT_SITES_ALL.CUST_ACCT_SITE_ID','=','HZ_CUST_SITE_USES_ALL.CUST_ACCT_SITE_ID')
            ->where('HZ_CUST_SITE_USES_ALL.SITE_USE_CODE', 'SHIP_TO')
            ->where('HZ_PARTIES.PARTY_NAME', $customer_name)
            ->select('HZ_PARTIES.PARTY_ID',
            'HZ_PARTIES.PARTY_NAME',
            'HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID AS SOLD_TO_ORG_ID',
            'HZ_CUST_SITE_USES_ALL.SITE_USE_ID AS SHIP_TO_ORG_ID',
            'HZ_CUST_SITE_USES_ALL.BILL_TO_SITE_USE_ID AS INVOICE_TO_ORG_ID',
            'HZ_CUST_ACCOUNTS.PRICE_LIST_ID',
            'HZ_CUST_SITE_USES_ALL.PAYMENT_TERM_ID')
            ->first();
    }

    public function getOrderNumber($st_number)
    {
        // if(!str_contains($st_number, 'REF-')){
            return DB::connection('oracle')
                ->table('OE_ORDER_HEADERS_ALL')
                ->where('OE_ORDER_HEADERS_ALL.CUST_PO_NUMBER', $st_number)
                ->select('OE_ORDER_HEADERS_ALL.ORDER_NUMBER')
                ->first();
        // }
    }

    public function getSOR($datefrom, $dateto)
    {
        return DB::connection('oracle')
            ->table('OE_ORDER_HEADERS_ALL')
            ->join('OE_ORDER_LINES_ALL','OE_ORDER_HEADERS_ALL.HEADER_ID','=','OE_ORDER_LINES_ALL.HEADER_ID')
            ->join('RCV_SHIPMENT_LINES','OE_ORDER_HEADERS_ALL.HEADER_ID','=','RCV_SHIPMENT_LINES.OE_ORDER_HEADER_ID')
            ->join('RCV_SHIPMENT_HEADERS','RCV_SHIPMENT_LINES.SHIPMENT_HEADER_ID','=','RCV_SHIPMENT_HEADERS.SHIPMENT_HEADER_ID')            
            ->join('HZ_CUST_ACCOUNTS','OE_ORDER_HEADERS_ALL.SOLD_TO_ORG_ID','=','HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID')
            ->join('AR_CUSTOMERS','HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID','=','AR_CUSTOMERS.CUSTOMER_ID')
            ->join('RCV_TRANSACTIONS','RCV_SHIPMENT_HEADERS.SHIPMENT_HEADER_ID','=','RCV_TRANSACTIONS.SHIPMENT_HEADER_ID')
            ->whereColumn('OE_ORDER_LINES_ALL.LINE_ID', 'RCV_SHIPMENT_LINES.OE_ORDER_LINE_ID')
            ->where('RCV_SHIPMENT_LINES.SOURCE_DOCUMENT_CODE', 'RMA')
            ->where('RCV_TRANSACTIONS.TRANSACTION_TYPE', 'RECEIVE')
            ->whereBetween('RCV_TRANSACTIONS.TRANSACTION_DATE', [$datefrom.' 00:00:00', $dateto.' 00:00:00'])
            ->select('RCV_TRANSACTIONS.TRANSACTION_DATE AS RECEIVED_DATE',
                'OE_ORDER_HEADERS_ALL.CUST_PO_NUMBER AS CUSTOMER_PO_NUMBER',
                'OE_ORDER_HEADERS_ALL.ORDER_NUMBER AS SOR_NUMBER',
                'AR_CUSTOMERS.CUSTOMER_NAME',
                'OE_ORDER_LINES_ALL.ORDERED_ITEM',
                'OE_ORDER_LINES_ALL.ORDERED_QUANTITY',
                'RCV_SHIPMENT_LINES.QUANTITY_RECEIVED',
                'RCV_SHIPMENT_LINES.ITEM_ID AS BEA_ITEM_ID',
                'RCV_SHIPMENT_LINES.ITEM_DESCRIPTION',
                'RCV_SHIPMENT_LINES.SOURCE_DOCUMENT_CODE')
            ->orderBy('RCV_TRANSACTIONS.TRANSACTION_DATE','ASC')->get();

    }

    public function getMOR($datefrom, $dateto)
    {
        return DB::connection('oracle')
            ->table('RCV_SHIPMENT_HEADERS')
            ->join('RCV_SHIPMENT_LINES','RCV_SHIPMENT_HEADERS.SHIPMENT_HEADER_ID','=','RCV_SHIPMENT_LINES.SHIPMENT_HEADER_ID')
            ->join('MTL_SYSTEM_ITEMS_B','RCV_SHIPMENT_LINES.ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
            ->join('RCV_TRANSACTIONS','RCV_SHIPMENT_LINES.SHIPMENT_LINE_ID','=','RCV_TRANSACTIONS.SHIPMENT_LINE_ID')
            ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID', 223)
            ->where('RCV_SHIPMENT_LINES.SOURCE_DOCUMENT_CODE', 'INVENTORY')
            ->where('RCV_TRANSACTIONS.TRANSACTION_TYPE', 'RECEIVE')
            ->whereBetween('RCV_TRANSACTIONS.TRANSACTION_DATE', [$datefrom.' 00:00:00', $dateto.' 00:00:00'])
            ->select('RCV_TRANSACTIONS.TRANSACTION_DATE AS RECEIVED_DATE',
                'RCV_SHIPMENT_HEADERS.SHIPMENT_NUM AS MOR_NUMBER',
                'RCV_SHIPMENT_LINES.QUANTITY_SHIPPED',
                'RCV_SHIPMENT_LINES.QUANTITY_RECEIVED',
                'MTL_SYSTEM_ITEMS_B.SEGMENT1 AS ORDERED_ITEM',
                'RCV_SHIPMENT_LINES.ITEM_DESCRIPTION',
                'RCV_SHIPMENT_LINES.SOURCE_DOCUMENT_CODE')
            ->get();
        //and RSL.SHIPMENT_LINE_ID = rcv.SHIPMENT_LINE_ID
    }

    public function getSORDetails($order_number)
    {
        return DB::connection('oracle')
            ->table('OE_ORDER_HEADERS_ALL')
            ->join('OE_ORDER_LINES_ALL','OE_ORDER_HEADERS_ALL.HEADER_ID','=','OE_ORDER_LINES_ALL.HEADER_ID')
            ->join('HZ_CUST_SITE_USES_ALL','OE_ORDER_HEADERS_ALL.SHIP_TO_ORG_ID','=','HZ_CUST_SITE_USES_ALL.SITE_USE_ID')
            ->join('HZ_CUST_ACCT_SITES_ALL','HZ_CUST_SITE_USES_ALL.CUST_ACCT_SITE_ID','=','HZ_CUST_ACCT_SITES_ALL.CUST_ACCT_SITE_ID')
            ->join('HZ_PARTY_SITES','HZ_CUST_ACCT_SITES_ALL.PARTY_SITE_ID','=','HZ_PARTY_SITES.PARTY_SITE_ID')
            ->join('HZ_CUST_ACCOUNTS_ALL','HZ_CUST_ACCT_SITES_ALL.CUST_ACCOUNT_ID','=','HZ_CUST_ACCOUNTS_ALL.CUST_ACCOUNT_ID')
            ->join('HZ_PARTIES','HZ_PARTY_SITES.PARTY_ID','=','HZ_PARTIES.PARTY_ID')
            
            ->where('OE_ORDER_HEADERS_ALL.ORDER_NUMBER', $order_number)
            ->select('OE_ORDER_HEADERS_ALL.HEADER_ID',
                'OE_ORDER_LINES_ALL.LINE_ID',
                'OE_ORDER_LINES_ALL.INVENTORY_ITEM_ID',
                'OE_ORDER_LINES_ALL.ORDERED_QUANTITY',
                'OE_ORDER_LINES_ALL.SUBINVENTORY',
                'HZ_PARTIES.PARTY_NAME',
                'HZ_CUST_ACCOUNTS_ALL.CUST_ACCOUNT_ID AS CUSTOMER_ID',
                'HZ_CUST_ACCT_SITES_ALL.CUST_ACCT_SITE_ID AS CUSTOMER_SITE_ID')
            ->get();
        
    }

    public function getBEAItemInventoryId($item_code)
    {
        return  DB::connection('oracle')->table('MTL_SYSTEM_ITEMS')
            ->where('ORGANIZATION_ID', 223)
            ->where('SEGMENT1', $item_code)->first();
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

    public function updateSerializedItems()
    {
        $pending_items = DB::table('ebs_pull')->where('status','PENDING')->select('ordered_item')->distinct()->get();
        
        foreach ($pending_items as $pending) {
            
            $item = DB::table('items')->where('digits_code', $pending->ordered_item)->first();

            DB::table('ebs_pull')->where('ordered_item',$pending->ordered_item)->where('status','PENDING')->update([
                'has_serial' => (is_null($item->has_serial)) ? 0 : $item->has_serial,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
    
    public function getWrrManual($datefrom,$dateto)
    {

        $wrrDetails = DB::connection('oracle')->table('PO_HEADERS_ALL')
        ->join('PO_LINES_ALL','PO_HEADERS_ALL.PO_HEADER_ID','=','PO_LINES_ALL.PO_HEADER_ID')
        ->join('RCV_SHIPMENT_LINES','PO_LINES_ALL.PO_LINE_ID','=','RCV_SHIPMENT_LINES.PO_LINE_ID')
        ->join('MTL_SYSTEM_ITEMS_B','PO_LINES_ALL.ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
        ->whereNotNull('MTL_SYSTEM_ITEMS_B.SEGMENT1')
        ->where('RCV_SHIPMENT_LINES.TO_ORGANIZATION_ID',224)
        ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID',223)
        ->whereBetween('RCV_SHIPMENT_LINES.CREATION_DATE',[$datefrom.' 00:00:00', $dateto.' 23:59:59'])
        ->select('PO_HEADERS_ALL.CREATION_DATE',
        'PO_HEADERS_ALL.SEGMENT1 as PO_NUMBER',
        'MTL_SYSTEM_ITEMS_B.SEGMENT1 as DIGITS_CODE',
        'RCV_SHIPMENT_LINES.CREATION_DATE as RECEIVED_DATE',
        'RCV_SHIPMENT_LINES.SHIPMENT_LINE_STATUS_CODE')
        ->orderBy('RCV_SHIPMENT_LINES.CREATION_DATE','ASC')
        ->get();
        
        if(!empty($wrrDetails)){
            foreach ($wrrDetails as $key => $value) {
                $existingItem = DB::connection('imfs')->table('item_masters')->where('digits_code', $value->digits_code)->first();
                $data = array();
                if(!is_null($existingItem)){
                    if(empty($existingItem->initial_wrr_date) || is_null($existingItem->initial_wrr_date)){
                        $data = [
                            'initial_wrr_date' => date('Y-m-d', strtotime((string)$value->received_date)),
                            'latest_wrr_date' => date('Y-m-d', strtotime((string)$value->received_date))
                        ];
                    }
                    else{
                        $data = self::getLatestWRRDate($value->digits_code, $value->received_date);
                    }
                    //update dimfs
                    $updatedItem = DB::connection('imfs')->table('item_masters')->where('digits_code', $value->digits_code)->update($data);
                }
                else{
                    $existingGashaItem = DB::connection('imfs')->table('gacha_item_masters')->where('digits_code', $value->digits_code)->first();
                    if(empty($existingGashaItem->initial_wrr_date) || is_null($existingGashaItem->initial_wrr_date)){
                        $data = [
                            'initial_wrr_date' => date('Y-m-d', strtotime((string)$value->received_date)),
                            'latest_wrr_date' => date('Y-m-d', strtotime((string)$value->received_date))
                        ];
                    }
                    else{
                        $data = self::getLatestWRRDate($value->digits_code, $value->received_date, 'gacha_item_masters');
                    }
                    //update gimfs
                    $updatedItem = DB::connection('imfs')->table('gacha_item_masters')->where('digits_code', $value->digits_code)->update($data);
                }
                
               \Log::notice($value->digits_code.' - '.$updatedItem);
            }
        }
    }
    
    public function getWrr()
    {
        $datefrom = date("Y-m-d H:00:00", strtotime("-5 hour"));
        $dateto = date("Y-m-d H:00:00", strtotime("-1 hour"));

        $wrrDetails = DB::connection('oracle')->table('PO_HEADERS_ALL')
        ->join('PO_LINES_ALL','PO_HEADERS_ALL.PO_HEADER_ID','=','PO_LINES_ALL.PO_HEADER_ID')
        ->join('RCV_SHIPMENT_LINES','PO_LINES_ALL.PO_LINE_ID','=','RCV_SHIPMENT_LINES.PO_LINE_ID')
        ->join('MTL_SYSTEM_ITEMS_B','PO_LINES_ALL.ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
        ->whereNotNull('MTL_SYSTEM_ITEMS_B.SEGMENT1')
        ->where('RCV_SHIPMENT_LINES.TO_ORGANIZATION_ID',224)
        ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID',223)
        ->whereBetween('RCV_SHIPMENT_LINES.CREATION_DATE',[$datefrom, $dateto])
        ->select('PO_HEADERS_ALL.CREATION_DATE',
        'PO_HEADERS_ALL.SEGMENT1 as PO_NUMBER',
        'MTL_SYSTEM_ITEMS_B.SEGMENT1 as DIGITS_CODE',
        'RCV_SHIPMENT_LINES.CREATION_DATE as RECEIVED_DATE',
        'RCV_SHIPMENT_LINES.SHIPMENT_LINE_STATUS_CODE')
        ->orderBy('RCV_SHIPMENT_LINES.CREATION_DATE','ASC')
        ->get();
        
        if(!empty($wrrDetails)){
            foreach ($wrrDetails as $key => $value) {
                $existingItem = DB::connection('imfs')->table('item_masters')->where('digits_code', $value->digits_code)->first();
                $data = array();
                if(!is_null($existingItem)){
                    if(empty($existingItem->initial_wrr_date) || is_null($existingItem->initial_wrr_date)){
                        $data = [
                            'initial_wrr_date' => date('Y-m-d', strtotime((string)$value->received_date)),
                            'latest_wrr_date' => date('Y-m-d', strtotime((string)$value->received_date))
                        ];
                    }
                    else{
                        $data = self::getLatestWRRDate($value->digits_code, $value->received_date);
                    }
                    //update dimfs
                    $updatedItem = DB::connection('imfs')->table('item_masters')->where('digits_code', $value->digits_code)->update($data);
                }
                else{
                    $existingGashaItem = DB::connection('imfs')->table('gacha_item_masters')->where('digits_code', $value->digits_code)->first();
                    if(empty($existingGashaItem->initial_wrr_date) || is_null($existingGashaItem->initial_wrr_date)){
                        $data = [
                            'initial_wrr_date' => date('Y-m-d', strtotime((string)$value->received_date)),
                            'latest_wrr_date' => date('Y-m-d', strtotime((string)$value->received_date))
                        ];
                    }
                    else{
                        $data = self::getLatestWRRDate($value->digits_code, $value->received_date, 'gacha_item_masters');
                    }
                    //update gimfs
                    $updatedItem = DB::connection('imfs')->table('gacha_item_masters')->where('digits_code', $value->digits_code)->update($data);
                }
               \Log::notice($value->digits_code.' - '.$updatedItem);
            }
        }
    }

    public static function getLatestWRRDate($digits_code, $latest_wrr_date, $table='item_masters')
	{
		$data = array();
		$existingItemLatestWRR = DB::connection('imfs')->table($table)->where('digits_code', intval($digits_code))->value('latest_wrr_date');
		$first = new Carbon((string)$existingItemLatestWRR);
		$second = new Carbon((string)$latest_wrr_date);
		
		if($first->gte($second)){
			$data = [
				'latest_wrr_date' => $existingItemLatestWRR
			];
		}
		elseif(!is_null($latest_wrr_date)){
			$data = [
				'latest_wrr_date' => date('Y-m-d', strtotime((string)$latest_wrr_date))
			];
		}
		else{
			$data = [
				'latest_wrr_date' => $existingItemLatestWRR
			];
		}
		return $data;
	}
	
	public function getDistriOnhand()
    {
        
        $distriSubs = DB::table('distri_subinventories')->where('status','ACTIVE')->orderBy('subinventory','ASC')->get();
        foreach($distriSubs as $distriSub) {
            $subCol = strtolower(str_replace(" ","_", $distriSub->subinventory));
            $new_column = date('Ymd').'_'.$subCol.'_reserved_qty';
            //create new order
            Schema::table('items', function (Blueprint $table) use ($new_column) {
                if (!Schema::hasColumn('items', $new_column)) {
                    $table->integer($new_column,false,true)->length(10)->default(0)->after('shopee_reserve_qty');
                }
            });
    
            $onhandqty = DB::connection('oracle')->table('MTL_ONHAND_TOTAL_MWB_V')
                ->join('MTL_SYSTEM_ITEMS_B','MTL_ONHAND_TOTAL_MWB_V.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
                ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID',223)
                ->where('MTL_ONHAND_TOTAL_MWB_V.SUBINVENTORY_CODE', $distriSub->subinventory)
                ->select(
                    'MTL_SYSTEM_ITEMS_B.SEGMENT1 as ITEM_CODE',
                    'MTL_ONHAND_TOTAL_MWB_V.SUBINVENTORY_CODE',
                    DB::raw("SUM (MTL_ONHAND_TOTAL_MWB_V.ON_HAND) as QUANTITY")
                )
                ->groupBy(
                    'MTL_SYSTEM_ITEMS_B.SEGMENT1',
                    'MTL_ONHAND_TOTAL_MWB_V.SUBINVENTORY_CODE'
                )->get();
            
           
            $cnt = 0;
            foreach ($onhandqty as $key => $value) {
                //update reserve qty
                $updated = DB::table('items')
                ->where('digits_code',$value->item_code)
                ->update([
                    $subCol.'_reserve_qty' => $value->quantity,
                    $new_column => $value->quantity
                ]);
    
                if($updated){
                    $cnt++;
                }
                    
            }
        }

        \Log::info('Done fetching Distri Onhand'.$cnt);
    }
	
	public function getRetailOnhand()
    {
        
        $new_column = date('Ymd').'_reserved_qty';
        //create new order
        Schema::table('items', function (Blueprint $table) use ($new_column) {
            if (!Schema::hasColumn('items', $new_column)) {
                $table->integer($new_column,false,true)->length(10)->default(0)->after('shopee_reserve_qty');
            }
        });
        
        $onhandqty = DB::connection('oracle')->table('MTL_ONHAND_TOTAL_MWB_V')
            ->join('MTL_SYSTEM_ITEMS_B','MTL_ONHAND_TOTAL_MWB_V.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
            ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID',223)
            ->where('MTL_ONHAND_TOTAL_MWB_V.ORGANIZATION_ID',223)
            ->where('MTL_ONHAND_TOTAL_MWB_V.SUBINVENTORY_CODE','RETAIL')
            ->select(
                'MTL_SYSTEM_ITEMS_B.SEGMENT1 as ITEM_CODE',
                'MTL_ONHAND_TOTAL_MWB_V.SUBINVENTORY_CODE',
                DB::raw("SUM (MTL_ONHAND_TOTAL_MWB_V.ON_HAND) as QUANTITY")
            )
            ->groupBy(
                'MTL_SYSTEM_ITEMS_B.SEGMENT1',
                'MTL_ONHAND_TOTAL_MWB_V.SUBINVENTORY_CODE'
            )->get();
       
        $cnt = 0;
        foreach ($onhandqty as $key => $value) {
            //update reserve qty
            $updated = DB::table('items')
            ->where('digits_code',$value->item_code)
            ->update([
                'reserve_qty' => $value->quantity,
                $new_column => $value->quantity
            ]);

            if($updated){
                $cnt++;
            }
                
        }

        \Log::info('Done fetching Retail Onhand'.$cnt);
    }

    public function getShopeeOnhand()
    {
        $onhandqty = DB::connection('oracle')->table('MTL_ONHAND_TOTAL_MWB_V')
            ->join('MTL_SYSTEM_ITEMS_B','MTL_ONHAND_TOTAL_MWB_V.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
            ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID',223)
            ->where('MTL_ONHAND_TOTAL_MWB_V.SUBINVENTORY_CODE','SHOPEE')
            ->select(
                'MTL_SYSTEM_ITEMS_B.SEGMENT1 as ITEM_CODE',
                'MTL_ONHAND_TOTAL_MWB_V.SUBINVENTORY_CODE',
                DB::raw("SUM (MTL_ONHAND_TOTAL_MWB_V.ON_HAND) as QUANTITY")
            )
            ->groupBy(
                'MTL_SYSTEM_ITEMS_B.SEGMENT1',
                'MTL_ONHAND_TOTAL_MWB_V.SUBINVENTORY_CODE'
            )->get();
       
        $cnt = 0;
        foreach ($onhandqty as $key => $value) {
            //update reserve qty
            $updated = DB::table('items')
            ->where('digits_code',$value->item_code)
            ->update([
                'shopee_reserve_qty' => $value->quantity
            ]);

            if($updated){
                $cnt++;
            }
                
        }

        \Log::info('Done fetching Shopee Onhand'.$cnt);
    }

    public function getLazadaOnhand()
    {
        $onhandqty = DB::connection('oracle')->table('MTL_ONHAND_TOTAL_MWB_V')
            ->join('MTL_SYSTEM_ITEMS_B','MTL_ONHAND_TOTAL_MWB_V.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
            ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID',223)
            ->where('MTL_ONHAND_TOTAL_MWB_V.SUBINVENTORY_CODE','LAZADA')
            ->select(
                'MTL_SYSTEM_ITEMS_B.SEGMENT1 as ITEM_CODE',
                'MTL_ONHAND_TOTAL_MWB_V.SUBINVENTORY_CODE',
                DB::raw("SUM (MTL_ONHAND_TOTAL_MWB_V.ON_HAND) as QUANTITY")
            )
            ->groupBy(
                'MTL_SYSTEM_ITEMS_B.SEGMENT1',
                'MTL_ONHAND_TOTAL_MWB_V.SUBINVENTORY_CODE'
            )->get();
       
        $cnt = 0;
        foreach ($onhandqty as $key => $value) {
            //update reserve qty
            $updated = DB::table('items')
            ->where('digits_code',$value->item_code)
            ->update([
                'lazada_reserve_qty' => $value->quantity
            ]);

            if($updated){
                $cnt++;
            }
                
        }

        \Log::info('Done fetching Lazada Onhand'.$cnt);
    }
    
    public function getReceivedDOTTransactions()
    {
        
        $pullouts = DB::table('pullout')
            ->where('status','FOR RECEIVING')
            ->select('st_document_number','channel_id','transaction_type','wh_from','wh_to')
            ->distinct()->get();

        foreach ($pullouts as $pullout) {
            $dot = DB::connection('oracle')->table('RCV_SHIPMENT_HEADERS')
                ->join('RCV_SHIPMENT_LINES','RCV_SHIPMENT_HEADERS.SHIPMENT_HEADER_ID','=','RCV_SHIPMENT_LINES.SHIPMENT_HEADER_ID')
                ->where('RCV_SHIPMENT_HEADERS.SHIPMENT_NUM', $pullout->st_document_number)
                ->select(
                    'RCV_SHIPMENT_HEADERS.ORGANIZATION_ID AS FROM_ORG',
                    'RCV_SHIPMENT_HEADERS.SHIP_TO_ORG_ID as TO_ORG',
                    'RCV_SHIPMENT_HEADERS.SHIPMENT_NUM',
                    'RCV_SHIPMENT_HEADERS.RECEIPT_NUM',
                    'RCV_SHIPMENT_HEADERS.SHIPPED_DATE',
                    DB::raw("SUM (RCV_SHIPMENT_LINES.QUANTITY_SHIPPED) as SUM_QTY_SHIPPED"),
                    DB::raw("SUM (RCV_SHIPMENT_LINES.QUANTITY_RECEIVED) as SUM_QTY_RECEIVED")
                )
                ->groupBy(
                    'RCV_SHIPMENT_HEADERS.ORGANIZATION_ID',
                    'RCV_SHIPMENT_HEADERS.SHIP_TO_ORG_ID',
                    'RCV_SHIPMENT_HEADERS.SHIPMENT_NUM',
                    'RCV_SHIPMENT_HEADERS.RECEIPT_NUM',
                    'RCV_SHIPMENT_HEADERS.SHIPPED_DATE'
                )
                ->first();

            //if retail
            $posItemDetails = array();
            $received_st_number = 0;
			$store = DB::table('stores')->where('pos_warehouse',$pullout->wh_from)->where('status','ACTIVE')->first();
            $pulloutItems = DB::table('pullout')
                ->where('st_document_number',$pullout->st_document_number)
                ->where('status','FOR RECEIVING')->get();
			
            foreach ($pulloutItems as $key_item => $value_item) {
				$serial = $value_item->item_code.'_serial_number';
				$price = DB::table('items')->where('digits_code',$value_item->item_code)->value('store_cost');
				if($value_item->has_serial == 1){
					$pulloutSerials = explode(",",$value_item->serial);
					foreach ($pulloutSerials as $key_serial => $value_serial) {
						
						$posItemDetails[$value_item->item_code.'-'.$key_serial] = [
							'item_code' => $value_item->item_code,
							'quantity' => 1,
							'serial_number' => $value_serial,
							'item_price' => $price
						];
					}
					
				}
				else{
					
					$posItemDetails[$value_item->item_code.'-'.$key_item] = [
						'item_code' => $value_item->item_code,
						'quantity' => $value_item->quantity,
						'item_price' => $price
					];
					
				}
			}

			if(!empty($pulloutItems) && $pullout->channel_id != 4 && !empty($dot) && $dot->sum_qty_received > 0){
                if($pullout->transaction_type == 'STW'){
                    $postedST = app(POSPushController::class)->posCreateStockTransfer($pullout->st_document_number, $store->pos_warehouse_transit_branch, $store->pos_warehouse_transit, $pullout->wh_to, 'STW-'.$store->pos_warehouse_name, $posItemDetails);
                }
                else{
                    $postedST = app(POSPushController::class)->posCreateStockTransfer($pullout->st_document_number, $store->pos_warehouse_rma_branch, $store->pos_warehouse_rma, $pullout->wh_to, 'STR-'.$store->pos_warehouse_name, $posItemDetails);
                }
                \Log::info('received ST:'.json_encode($postedST));
                $received_st_number = $postedST['data']['record']['fdocument_no'];
                if($postedST['data']['record']['fresult'] != "ERROR" && !empty($received_st_number)){
                    if($pullout->transaction_type == 'STW'){
                        app(POSPushController::class)->posCreateStockAdjustmentOut($received_st_number,'DIGITSWAREHOUSE', $posItemDetails);
                    }
                    
                }
            }

            if(!empty($dot) && $dot->sum_qty_received > 0 && $dot->sum_qty_received == $dot->sum_qty_shipped){
                DB::table('pullout')->where('st_document_number',$pullout->st_document_number)->update([
                    'received_st_date' => date('Y-m-d'),
                    'received_st_number' => ($pullout->channel_id != 4) ? $received_st_number : NULL,
                    'status' => 'RECEIVED'
                ]);
            }
            elseif(!empty($dot) && $dot->sum_qty_received > 0 && $dot->sum_qty_received < $dot->sum_qty_shipped){
                DB::table('pullout')->where('st_document_number',$pullout->st_document_number)->update([
                    'received_st_date' => date('Y-m-d'),
                    'received_st_number' => ($pullout->channel_id != 4) ? $received_st_number : NULL,
                    'status' => 'PARTIALLY RECEIVED'
                ]);
            }

        }
        
    }
    
    public function getMORTransactions()
    {
        $pulloutDetails = DB::table('pullout')
            ->where('status','FOR PROCESSING')
            ->select('st_document_number','transport_types_id')
            ->distinct()->get();
            
        foreach ($pulloutDetails as $pullout) {
            
            $mor = DB::connection('oracle')->table('RCV_SHIPMENT_HEADERS')->where('SHIPMENT_NUM', $pullout->st_document_number)->first();

            if(!empty($mor)){
                if($pullout->channel_id != 4){
                    DB::table('pullout')->where('st_document_number',$pullout->st_document_number)->update([
                        'sor_number' => $pullout->st_document_number, 
                        'status' => ($pullout->transport_types_id == 1) ? 'FOR SCHEDULE' : 'FOR RECEIVING',
                        'step' => ($pullout->transport_types_id == 1) ? 4 : 5 
                    ]);
                }
                else{
                    DB::table('pullout')->where('st_document_number',$pullout->st_document_number)->update([
                        'sor_number' => $pullout->st_document_number,
                        'status' => ($pullout->transport_types_id == 1) ? 'FOR SCHEDULE' : 'FOR RECEIVING',
                        'step' => ($pullout->transport_types_id == 1) ? 4 : 5 
                    ]);
                }
            }
            
        }
        
    }
    
    public function getReceivedSORTransactions()
    {
        $pullouts = DB::table('pullout')->where('status','FOR RECEIVING')
            ->where('transaction_type','RMA')
            ->where(DB::raw('substr(wh_from, -3)'), '=', 'FBV')
            ->where('channel_id',4)->distinct('st_document_number')->get();

        foreach ($pullouts as $pullout) {
            
            if(is_numeric($pullout->sor_number)){
                
                $sor = DB::connection('oracle')
                    ->table('OE_ORDER_HEADERS_ALL')
                    ->join('OE_ORDER_LINES_ALL','OE_ORDER_HEADERS_ALL.HEADER_ID','=','OE_ORDER_LINES_ALL.HEADER_ID')
                    ->where('OE_ORDER_HEADERS_ALL.ORDER_NUMBER', $pullout->sor_number)
                    ->select(
                        'OE_ORDER_HEADERS_ALL.ORDER_NUMBER',
                        DB::raw("SUM (OE_ORDER_LINES_ALL.ORDERED_QUANTITY) as SUM_QTY_ORDERED"),
                        DB::raw("SUM (OE_ORDER_LINES_ALL.SHIPPED_QUANTITY) as SUM_QTY_SHIPPED")
                    )->groupBy('OE_ORDER_HEADERS_ALL.ORDER_NUMBER')->first();
    
                if(!empty($sor) && $sor->sum_qty_shipped > 0 && $sor->sum_qty_shipped == $sor->sum_qty_ordered){
                    DB::table('pullout')->where('st_document_number',$pullout->st_document_number)->update([
                        'received_st_date' => date('Y-m-d'),
                        'status' => 'RECEIVED'
                    ]);
                }
                if(!empty($sor) && $sor->sum_qty_shipped > 0 && $sor->sum_qty_shipped < $sor->sum_qty_ordered){
                    DB::table('pullout')->where('st_document_number',$pullout->st_document_number)->update([
                        'received_st_date' => date('Y-m-d'),
                        'status' => 'PARTIALLY RECEIVED'
                    ]);
                }
            
            }
        }
    }
    
    public function getReceivedDistriSORTransactions()
    {
        $pullouts = DB::table('pullout')->where('status','FOR RECEIVING')
            ->where('transaction_type','RMA')
            ->whereIn('channel_id',[7,10,11])->distinct('st_document_number')->get();

        foreach ($pullouts as $pullout) {
            
            if(is_numeric($pullout->sor_number)){
                
                $sor = DB::connection('oracle')
                    ->table('OE_ORDER_HEADERS_ALL')
                    ->join('OE_ORDER_LINES_ALL','OE_ORDER_HEADERS_ALL.HEADER_ID','=','OE_ORDER_LINES_ALL.HEADER_ID')
                    ->where('OE_ORDER_HEADERS_ALL.ORDER_NUMBER', $pullout->sor_number)
                    ->select(
                        'OE_ORDER_HEADERS_ALL.ORDER_NUMBER',
                        DB::raw("SUM (OE_ORDER_LINES_ALL.ORDERED_QUANTITY) as SUM_QTY_ORDERED"),
                        DB::raw("SUM (OE_ORDER_LINES_ALL.SHIPPED_QUANTITY) as SUM_QTY_SHIPPED")
                    )->groupBy('OE_ORDER_HEADERS_ALL.ORDER_NUMBER')->first();
    
                if(!empty($sor) && $sor->sum_qty_shipped > 0 && $sor->sum_qty_shipped == $sor->sum_qty_ordered){
                    DB::table('pullout')->where('st_document_number',$pullout->st_document_number)->update([
                        'received_st_date' => date('Y-m-d'),
                        'status' => 'RECEIVED'
                    ]);
                }
                if(!empty($sor) && $sor->sum_qty_shipped > 0 && $sor->sum_qty_shipped < $sor->sum_qty_ordered){
                    DB::table('pullout')->where('st_document_number',$pullout->st_document_number)->update([
                        'received_st_date' => date('Y-m-d'),
                        'status' => 'PARTIALLY RECEIVED'
                    ]);
                }
            
            }
        }
    }

    //GIS MW STR Receiving
    public function getReceivedSORGisMwTransactions()
    {
        $pullouts = DB::table('pullout')->where('status','FOR RECEIVING')
            ->whereIn('transaction_type',['RMA','STW'])
            ->whereNotNull('request_type')
    	    ->distinct('st_document_number')->get();
    
        foreach ($pullouts as $pullout) {
            
            if(is_numeric($pullout->sor_number)){
                
                $sor = DB::connection('oracle')
                    ->table('OE_ORDER_HEADERS_ALL')
                    ->join('OE_ORDER_LINES_ALL','OE_ORDER_HEADERS_ALL.HEADER_ID','=','OE_ORDER_LINES_ALL.HEADER_ID')
                    ->where('OE_ORDER_HEADERS_ALL.ORDER_NUMBER', $pullout->sor_number)
                    ->select(
                        'OE_ORDER_HEADERS_ALL.ORDER_NUMBER',
                        DB::raw("SUM (OE_ORDER_LINES_ALL.ORDERED_QUANTITY) as SUM_QTY_ORDERED"),
                        DB::raw("SUM (OE_ORDER_LINES_ALL.SHIPPED_QUANTITY) as SUM_QTY_SHIPPED")
                    )->groupBy('OE_ORDER_HEADERS_ALL.ORDER_NUMBER')->first();
    
                if(!empty($sor) && $sor->sum_qty_shipped > 0 && $sor->sum_qty_shipped == $sor->sum_qty_ordered){
                    DB::table('pullout')->where('st_document_number',$pullout->st_document_number)->update([
                        'received_st_date' => date('Y-m-d'),
                        'status' => 'RECEIVED'
                    ]);
                }
                if(!empty($sor) && $sor->sum_qty_shipped > 0 && $sor->sum_qty_shipped < $sor->sum_qty_ordered){
                    DB::table('pullout')->where('st_document_number',$pullout->st_document_number)->update([
                        'received_st_date' => date('Y-m-d'),
                        'status' => 'PARTIALLY RECEIVED'
                    ]);
                }
            
            }
        }
    }

    public function getReceivedSORFBDTransactions()
    {
        $pullouts = DB::table('pullout')->where('status','FOR RECEIVING')
            ->where('channel_id',4)
            ->where('transaction_type','RMA')
            ->where(DB::raw('substr(wh_from, -3)'), '=', 'FBD')
            ->select('st_document_number','sor_number','transaction_type','wh_from','wh_to')
            ->distinct()->get();

        foreach ($pullouts as $pullout) {
            
            if(is_numeric($pullout->sor_number)){
                
                $sor = DB::connection('oracle')
                ->table('OE_ORDER_HEADERS_ALL')
                ->join('OE_ORDER_LINES_ALL','OE_ORDER_HEADERS_ALL.HEADER_ID','=','OE_ORDER_LINES_ALL.HEADER_ID')
                ->where('OE_ORDER_HEADERS_ALL.ORDER_NUMBER', $pullout->sor_number)
                ->select(
                    'OE_ORDER_HEADERS_ALL.ORDER_NUMBER',
                    DB::raw("SUM (OE_ORDER_LINES_ALL.ORDERED_QUANTITY) as SUM_QTY_ORDERED"),
                    DB::raw("SUM (OE_ORDER_LINES_ALL.SHIPPED_QUANTITY) as SUM_QTY_SHIPPED")
                )->groupBy('OE_ORDER_HEADERS_ALL.ORDER_NUMBER')->first();
    
                $posItemDetails = array();
                $received_st_number = 0;
    			$store = DB::table('stores')->where('pos_warehouse',$pullout->wh_from)->where('status','ACTIVE')->first();
                $pulloutItems = DB::table('pullout')
                    ->where('st_document_number',$pullout->st_document_number)
                    ->where('status','FOR RECEIVING')->get();
    			
                foreach ($pulloutItems as $key_item => $value_item) {
    				$serial = $value_item->item_code.'_serial_number';
    				$price = DB::table('items')->where('digits_code',$value_item->item_code)->value('store_cost');
    				if($value_item->has_serial == 1){
    					$pulloutSerials = explode(",",$value_item->serial);
    					foreach ($pulloutSerials as $key_serial => $value_serial) {
    						
    						$posItemDetails[$value_item->item_code.'-'.$key_serial] = [
    							'item_code' => $value_item->item_code,
    							'quantity' => 1,
    							'serial_number' => $value_serial,
    							'item_price' => $price
    						];
    					}
    					
    				}
    				else{
    					
    					$posItemDetails[$value_item->item_code.'-'.$key_item] = [
    						'item_code' => $value_item->item_code,
    						'quantity' => $value_item->quantity,
    						'item_price' => $price
    					];
    					
    				}
    			}
    
    			if(!empty($pulloutItems) && !empty($sor) && $sor->sum_qty_shipped > 0){
                    $postedST = app(POSPushController::class)->posCreateStockTransfer($pullout->st_document_number, $store->pos_warehouse_rma_branch, $store->pos_warehouse_rma, $pullout->wh_to, 'STR-'.$store->pos_warehouse_name, $posItemDetails);
                    
                    \Log::info('received ST:'.json_encode($postedST));
                    $received_st_number = $postedST['data']['record']['fdocument_no'];
                    
                }
    
                if(!empty($sor) && $sor->sum_qty_shipped > 0 && $sor->sum_qty_shipped == $sor->sum_qty_ordered){
                    DB::table('pullout')->where('st_document_number',$pullout->st_document_number)->update([
                        'received_st_date' => date('Y-m-d'),
                        'received_st_number' => $received_st_number,
                        'status' => 'RECEIVED'
                    ]);
                }
                if(!empty($sor) && $sor->sum_qty_shipped > 0 && $sor->sum_qty_shipped < $sor->sum_qty_ordered){
                    DB::table('pullout')->where('st_document_number',$pullout->st_document_number)->update([
                        'received_st_date' => date('Y-m-d'),
                        'received_st_number' => $received_st_number,
                        'status' => 'PARTIALLY RECEIVED'
                    ]);
                }
            }
        }
    }
    
    public function getReceivedSORFranchiseTransactions()
    {
        $pullouts = DB::table('pullout')->where('status','FOR RECEIVING')
            ->where('channel_id',2)
            ->select('st_document_number','sor_number','transaction_type','wh_from','wh_to')
            ->distinct()->get();

        foreach ($pullouts as $pullout) {
            $sor = DB::connection('oracle')
            ->table('OE_ORDER_HEADERS_ALL')
            ->join('OE_ORDER_LINES_ALL','OE_ORDER_HEADERS_ALL.HEADER_ID','=','OE_ORDER_LINES_ALL.HEADER_ID')
            ->where('OE_ORDER_HEADERS_ALL.ORDER_NUMBER', $pullout->sor_number)
            ->select(
                'OE_ORDER_HEADERS_ALL.ORDER_NUMBER',
                DB::raw("SUM (OE_ORDER_LINES_ALL.ORDERED_QUANTITY) as SUM_QTY_ORDERED"),
                DB::raw("SUM (OE_ORDER_LINES_ALL.SHIPPED_QUANTITY) as SUM_QTY_SHIPPED")
            )->groupBy('OE_ORDER_HEADERS_ALL.ORDER_NUMBER')->first();

            $posItemDetails = array();
            $received_st_number = 0;
			$store = DB::table('stores')->where('pos_warehouse',$pullout->wh_from)->where('status','ACTIVE')->first();
            $pulloutItems = DB::table('pullout')
                ->where('st_document_number',$pullout->st_document_number)
                ->where('status','FOR RECEIVING')->get();
			
            foreach ($pulloutItems as $key_item => $value_item) {
				$serial = $value_item->item_code.'_serial_number';
				$price = DB::table('items')->where('digits_code',$value_item->item_code)->value('store_cost');
				if($value_item->has_serial == 1){
					$pulloutSerials = explode(",",$value_item->serial);
					foreach ($pulloutSerials as $key_serial => $value_serial) {
						
						$posItemDetails[$value_item->item_code.'-'.$key_serial] = [
							'item_code' => $value_item->item_code,
							'quantity' => 1,
							'serial_number' => $value_serial,
							'item_price' => $price
						];
					}
					
				}
				else{
					
					$posItemDetails[$value_item->item_code.'-'.$key_item] = [
						'item_code' => $value_item->item_code,
						'quantity' => $value_item->quantity,
						'item_price' => $price
					];
					
				}
			}

			if(!empty($pulloutItems) && !empty($sor) && $sor->sum_qty_shipped > 0){
                if($pullout->transaction_type == 'STW'){
                    $postedST = app(POSPushController::class)->posCreateStockTransfer($pullout->st_document_number, $store->pos_warehouse_transit_branch, $store->pos_warehouse_transit, $pullout->wh_to, 'STW-'.$store->pos_warehouse_name, $posItemDetails);
                }
                else{
                    $postedST = app(POSPushController::class)->posCreateStockTransfer($pullout->st_document_number, $store->pos_warehouse_rma_branch, $store->pos_warehouse_rma, $pullout->wh_to, 'STR-'.$store->pos_warehouse_name, $posItemDetails);
                }
                \Log::info('received ST:'.json_encode($postedST));
                $received_st_number = $postedST['data']['record']['fdocument_no'];
                if($postedST['data']['record']['fresult'] != "ERROR" && !empty($received_st_number)){
                    if($pullout->transaction_type == 'STW'){
                        app(POSPushController::class)->posCreateStockAdjustmentOut($received_st_number,'DIGITSWAREHOUSE', $posItemDetails);
                    }
                    
                }
            }

            if(!empty($sor) && $sor->sum_qty_shipped > 0 && $sor->sum_qty_shipped == $sor->sum_qty_ordered){
                DB::table('pullout')->where('st_document_number',$pullout->st_document_number)->update([
                    'received_st_date' => date('Y-m-d'),
                    'received_st_number' => $received_st_number,
                    'status' => 'RECEIVED'
                ]);
            }
            if(!empty($sor) && $sor->sum_qty_shipped > 0 && $sor->sum_qty_shipped < $sor->sum_qty_ordered){
                DB::table('pullout')->where('st_document_number',$pullout->st_document_number)->update([
                    'received_st_date' => date('Y-m-d'),
                    'received_st_number' => $received_st_number,
                    'status' => 'PARTIALLY RECEIVED'
                ]);
            }
        }
    }
    
    public function getReceivedDOTDistri()
    {
        $drs = DB::table('ebs_pull')
            ->where('status','PENDING')
            ->where('customer_name','LIKE','%CON')
            ->select('dr_number','customer_name')
            ->distinct()->get();

        foreach ($drs as $dr) {
            $dot = DB::connection('oracle')->table('RCV_SHIPMENT_HEADERS')
                ->join('RCV_SHIPMENT_LINES','RCV_SHIPMENT_HEADERS.SHIPMENT_HEADER_ID','=','RCV_SHIPMENT_LINES.SHIPMENT_HEADER_ID')
                ->where('RCV_SHIPMENT_HEADERS.SHIPMENT_NUM', $dr->dr_number)
                ->select(
                    'RCV_SHIPMENT_HEADERS.ORGANIZATION_ID AS FROM_ORG',
                    'RCV_SHIPMENT_HEADERS.SHIP_TO_ORG_ID as TO_ORG',
                    'RCV_SHIPMENT_HEADERS.SHIPMENT_NUM',
                    'RCV_SHIPMENT_HEADERS.RECEIPT_NUM',
                    'RCV_SHIPMENT_HEADERS.SHIPPED_DATE',
                    DB::raw("SUM (RCV_SHIPMENT_LINES.QUANTITY_SHIPPED) as SUM_QTY_SHIPPED"),
                    DB::raw("SUM (RCV_SHIPMENT_LINES.QUANTITY_RECEIVED) as SUM_QTY_RECEIVED")
                )
                ->groupBy(
                    'RCV_SHIPMENT_HEADERS.ORGANIZATION_ID',
                    'RCV_SHIPMENT_HEADERS.SHIP_TO_ORG_ID',
                    'RCV_SHIPMENT_HEADERS.SHIPMENT_NUM',
                    'RCV_SHIPMENT_HEADERS.RECEIPT_NUM',
                    'RCV_SHIPMENT_HEADERS.SHIPPED_DATE'
                )
                ->first();

            if(!empty($dot) && $dot->sum_qty_received > 0 && $dot->sum_qty_received == $dot->sum_qty_shipped){
                DB::table('ebs_pull')->where('dr_number',$dr->dr_number)->update([
                    'received_at' => date('Y-m-d H:i:s'),
                    'st_document_number' => NULL,
                    'status' => 'RECEIVED'
                ]);
            }
            elseif(!empty($dot) && $dot->sum_qty_received > 0 && $dot->sum_qty_received < $dot->sum_qty_shipped){
                DB::table('ebs_pull')->where('dr_number',$dr->dr_number)->update([
                    'received_at' => date('Y-m-d H:i:s'),
                    'st_document_number' => NULL,
                    'status' => 'PARTIALLY RECEIVED'
                ]);
            }

        }
        
    }
    
    public function getShipmentRcvHeadersInterface($dr_number){
        return DB::connection('oracle')->table('RCV_HEADERS_INTERFACE')->where('SHIPMENT_NUM',$dr_number)->first();
    }
    
    public function getItemInterface($item_code){
        return DB::connection('oracle')->table('MTL_SYSTEM_ITEMS_INTERFACE')->where('segment1', $item_code)->first();
    }
    
    public function getCreatedMOR($dr_number){
        return DB::connection('oracle')->table('MTL_TRANSACTIONS_INTERFACE')->where('SHIPMENT_NUMBER',$dr_number)->first();
    }
    public function getCreatedItemMOR($dr_number,$item_code){
        return DB::connection('oracle')->table('MTL_TRANSACTIONS_INTERFACE')->where('SHIPMENT_NUMBER',$dr_number)->where('INVENTORY_ITEM_ID',$item_code)->first();
    }
}

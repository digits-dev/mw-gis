<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Item;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function getItemsCreatedAPI() {

        $uniqueString = time(); 
        
        $xAuthorizationToken = md5( config('item-master.secret') . $uniqueString . config('item-master.agent'));
        $xAuthorizationTime = $uniqueString;
        $vars = [
            "your_param"=>1
        ];

        //https://stackoverflow.com/questions/8115683/php-curl-custom-headers
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,config('item-master.item_create_link'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POST, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS,null);
        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 30);

        $headers = [
        'X-Authorization-Token: ' . $xAuthorizationToken,
        'X-Authorization-Time: ' . $xAuthorizationTime,
        'User-Agent: '.config('item-master.agent')
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec ($ch);
        curl_close ($ch);

        $response = json_decode($server_output, true);
        //dd($response);
        $data = array();
        $count = 0;
        if(!empty($response["data"])) {
            foreach ($response["data"] as $key => $value) {
                $count++;
                $existingItem = Item::where('digits_code',$value['digits_code'])->first();
                if(empty($existingItem)){
                    
                    DB::beginTransaction();
                    
                    try {
                        Item::insert([
                            'digits_code' => $value['digits_code'],
                            'upc_code' => $value['upc_code'],
                            'upc_code2' => $value['upc_code2'],
                            'upc_code3' => $value['upc_code3'],
                            'upc_code4' => $value['upc_code4'],
                            'upc_code5' => $value['upc_code5'],
                            'item_description' => $value['item_description'],
                            'brand' => $value['brand'],
                            'has_serial' => $value['has_serial'],
                            'store_cost' => (is_null($value['dtp_rf'])) ? 0.00 : $value['dtp_rf'],
                            'created_by' => $value['created_by'],
                            'created_at' => $value['created_at']
                        ]);
                        DB::commit();
                    } catch (\Exception $e) {
                        \Log::debug($e);
                        DB::rollback();
                    }
                }
            }
        }
        \Log::info('itemcreate: executed! '.$count.' items');
            
    }

    public function getItemsUpdatedAPI() {
         
        $uniqueString = time();
        $xAuthorizationToken = md5( config('item-master.secret') . $uniqueString . config('item-master.agent'));
        $xAuthorizationTime = $uniqueString;
        $vars = [
            "your_param"=>1
        ];

        //https://stackoverflow.com/questions/8115683/php-curl-custom-headers
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,config('item-master.item_update_link'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POST, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS,null);
        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 30);

        $headers = [
        'X-Authorization-Token: ' . $xAuthorizationToken,
        'X-Authorization-Time: ' . $xAuthorizationTime,
        'User-Agent: '.config('item-master.agent')
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec ($ch);
        curl_close ($ch);

        $response = json_decode($server_output, true);
        
        $cnt_success = 0;
        $cnt_fail = 0;
        $check_sync = false;
        if(!empty($response["data"])) {
            foreach ($response["data"] as $key => $value) {
                
                DB::beginTransaction();

                try {
                    $isItemUpdated = DB::table('items')
                    ->where('digits_code', $value['digits_code'])
                    ->update([
                        'upc_code' => $value['upc_code'],
                        'upc_code2' => $value['upc_code2'],
                        'upc_code3' => $value['upc_code3'],
                        'upc_code4' => $value['upc_code4'],
                        'upc_code5' => $value['upc_code5'],
                        'item_description' => $value['item_description'],
                        'brand' => $value['brand'],
                        'has_serial' => $value['has_serial'],
                        'store_cost' => (is_null($value['dtp_rf'])) ? 0.00 : $value['dtp_rf'],
                        'created_by' => $value['created_by'],
                        'created_at' => $value['created_at'],
                        'updated_by' => $value['updated_by'],
                        'updated_at' => $value['updated_at']
                    ]);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                }
                
                if ($isItemUpdated) {
                    $check_sync = true;
                    $cnt_success++;
                }
                else{
                    $check_sync = false;
                    $cnt_fail++;
                }
                
            }
        }
        \Log::info('itemupdate: executed! '.$cnt_success.' items');
        
    }

    public function pushBEAItemCreation()
    {
        $secretKey = "4fea7e3c217fa58b26d57fc186e906b4"; 
        $uniqueString = time(); 
        $userAgent = $_SERVER['HTTP_USER_AGENT']; 
        $userAgent = $_SERVER['HTTP_USER_AGENT']; 
        if($userAgent == '' || is_null($userAgent)){
            $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36';    
        }
        $xAuthorizationToken = md5( $secretKey . $uniqueString . $userAgent);
        $xAuthorizationTime = $uniqueString;
        $vars = [
            "your_param"=>1
        ];

        //https://stackoverflow.com/questions/8115683/php-curl-custom-headers
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://dimfs.digitstrading.ph/api/bea_items_created");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POST, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS,null);
        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 30);

        $headers = [
        'X-Authorization-Token: ' . $xAuthorizationToken,
        'X-Authorization-Time: ' . $xAuthorizationTime,
        'User-Agent: '.$userAgent
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec ($ch);
        curl_close ($ch);

        $response = json_decode($server_output, true);
        
        $cnt_success = 0;
        $cnt_fail = 0;
        $check_sync = false;

        $process_id = rand(1,999);
        // dd($response["data"]);
        if(!empty($response["data"])) {
            foreach ($response["data"] as $key => $value) {
                $existingItem = app(EBSPullController::class)->getItemInterface($value['digits_code']);
                
                if(empty($existingItem)){
                    DB::beginTransaction();
    
                    try {
                        app(EBSPushController::class)->createBEAItem(223, $process_id, 4086, $value['digits_code'], $value['upc_code'], $value['supplier_item_code'], $value['item_description'], $value['brand'], $value['warehouse_category'], $value['current_srp'], $value['purchase_price'], $value['has_serial']);
                        app(EBSPushController::class)->createBEAItem(224, $process_id, 4084, $value['digits_code'], $value['upc_code'], $value['supplier_item_code'], $value['item_description'], $value['brand'], $value['warehouse_category'], $value['current_srp'], $value['purchase_price'], $value['has_serial']);
                        app(EBSPushController::class)->createBEAItem(225, $process_id, 4085, $value['digits_code'], $value['upc_code'], $value['supplier_item_code'], $value['item_description'], $value['brand'], $value['warehouse_category'], $value['current_srp'], $value['purchase_price'], $value['has_serial']);
                        app(EBSPushController::class)->createBEAItem(263, $process_id, 8084, $value['digits_code'], $value['upc_code'], $value['supplier_item_code'], $value['item_description'], $value['brand'], $value['warehouse_category'], $value['current_srp'], $value['purchase_price'], $value['has_serial']);
                        DB::commit();
                    } catch (\Exception $e) {
                        \Log::debug($e);
                        DB::rollback();
                    }
                    $cnt_success++;
                }
                
            }
        }
        \Log::info('beaitemcreated: executed! '.$cnt_success.' items');
    }

    public function pushBEAItemUpdate()
    {
        $secretKey = "4fea7e3c217fa58b26d57fc186e906b4"; 
        $uniqueString = time(); 
        $userAgent = $_SERVER['HTTP_USER_AGENT']; 
        $userAgent = $_SERVER['HTTP_USER_AGENT']; 
        if($userAgent == '' || is_null($userAgent)){
            $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36';    
        }
        $xAuthorizationToken = md5( $secretKey . $uniqueString . $userAgent);
        $xAuthorizationTime = $uniqueString;
        $vars = [
            "your_param"=>1
        ];

        //https://stackoverflow.com/questions/8115683/php-curl-custom-headers
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://dimfs.digitstrading.ph/api/bea_items_updated");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POST, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS,null);
        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 30);

        $headers = [
        'X-Authorization-Token: ' . $xAuthorizationToken,
        'X-Authorization-Time: ' . $xAuthorizationTime,
        'User-Agent: '.$userAgent
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec ($ch);
        curl_close ($ch);

        $response = json_decode($server_output, true);
        
        $cnt_success = 0;
        $cnt_fail = 0;
        $check_sync = false;
        if(!empty($response["data"])) {
            foreach ($response["data"] as $key => $value) {
                
                DB::beginTransaction();

                try {
                    $isItemUpdated = DB::table('items')
                    ->where('digits_code', $value['digits_code'])
                    ->update([
                        'upc_code' => $value['upc_code'],
                        'upc_code2' => $value['upc_code2'],
                        'upc_code3' => $value['upc_code3'],
                        'upc_code4' => $value['upc_code4'],
                        'upc_code5' => $value['upc_code5'],
                        'item_description' => $value['item_description'],
                        'brand' => $value['brand'],
                        'has_serial' => $value['has_serial'],
                        'store_cost' => $value['dtp_rf'],
                        'created_by' => $value['created_by'],
                        'created_at' => $value['created_at'],
                        'updated_by' => $value['updated_by'],
                        'updated_at' => $value['updated_at']
                    ]);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                }
                
                if ($isItemUpdated) {
                    $check_sync = true;
                    $cnt_success++;
                }
                else{
                    $check_sync = false;
                    $cnt_fail++;
                }
                
            }
        }
        \Log::info('beaitemupdate: executed! '.$cnt_success.' items');
        // updateBEAItemSerial($item_code, $serial)
    }

    public function middlewareUpdateBEAItem()
    {
        $noBEAItemIds = DB::table('items')->whereNull('bea_item_id')->get();
        $cnt_success = 0;
        $cnt_fail = 0;

        foreach ($noBEAItemIds as $key => $value) {
            $bea_item =  app(EBSPullController::class)->getBEAItemInventoryId($value->digits_code);
            //update
            $isItemUpdated = DB::table('items')->where('digits_code', $value->digits_code)->update([
                'bea_item_id' => $bea_item->inventory_item_id
            ]);

            if ($isItemUpdated) {
                $cnt_success++;
            }
            else{
                $cnt_fail++;
            }
        }

        \Log::info('mwitemsupdated: executed! '.$cnt_success.' items');
    }

    public function pushPOSItemCreation()
    {
        $secretKey = "4fea7e3c217fa58b26d57fc186e906b4"; 
        $uniqueString = time(); 
        $userAgent = $_SERVER['HTTP_USER_AGENT']; 
        $userAgent = $_SERVER['HTTP_USER_AGENT']; 
        if($userAgent == '' || is_null($userAgent)){
            $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36';    
        }
        $xAuthorizationToken = md5( $secretKey . $uniqueString . $userAgent);
        $xAuthorizationTime = $uniqueString;
        $vars = [
            "your_param"=>1
        ];

        //https://stackoverflow.com/questions/8115683/php-curl-custom-headers
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://dimfs.digitstrading.ph/api/bea_items_created");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POST, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS,null);
        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 30);

        $headers = [
        'X-Authorization-Token: ' . $xAuthorizationToken,
        'X-Authorization-Time: ' . $xAuthorizationTime,
        'User-Agent: '.$userAgent
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec ($ch);
        curl_close ($ch);

        $response = json_decode($server_output, true);
        
        $cnt_success = 0;
        $cnt_fail = 0;
        $check_sync = false;
        $posItemDetails = array();

        if(!empty($response["data"])) {
            foreach ($response["data"] as $key => $value) {
                
                $existingPOSItem = app(POSPullController::class)->getPOSItem($value['digits_code']);
                
                if(empty($existingPOSItem['data']['record']['fproductid'])){
                    app(POSPushController::class)->posCreateItem(
                        $value['digits_code'], 
                        $value['upc_code'], 
                        $value['item_description'], 
                        $value['current_srp'], 
                        $value['dtp_rf'], 
                        $value['has_serial']);
                }
                
            }
        }
        \Log::info('positemcreated: executed! '.count((array)$response["data"]).' items');
    }
    
    public function pushBEAItemCreationManual($datefrom,$dateto)
    {
        
        $data_items = DB::connection('imfs')->table('item_masters')
        ->join('brands','item_masters.brands_id','=','brands.id')
        ->join('warehouse_categories','item_masters.warehouse_categories_id','=','warehouse_categories.id')
        ->whereBetween('approved_at',[$datefrom.' 00:00:00',$dateto.' 23:59:59'])
        ->select('item_masters.digits_code',
            'item_masters.upc_code',
            'item_masters.supplier_item_code',
            'item_masters.item_description',
            'item_masters.current_srp',
            'item_masters.purchase_price',
            'item_masters.has_serial',
            'brands.brand_description as brand',
            'warehouse_categories.warehouse_category_description as warehouse_category')
        ->get();
        
        $cnt_success = 0;
        $cnt_fail = 0;
        $check_sync = false;

        $process_id = rand(1,999);
        
        if(!empty($data_items)) {
            foreach ($data_items as $key => $value) {
                $existingItem = app(EBSPullController::class)->getItemInterface($value->digits_code);
                
                if(empty($existingItem)){
                    DB::beginTransaction();
    
                    try {
                        app(EBSPushController::class)->createBEAItem(223, $process_id, 4086, $value->digits_code, $value->upc_code, $value->supplier_item_code, $value->item_description, $value->brand, $value->warehouse_category, $value->current_srp, $value->purchase_price, $value->has_serial);
                        app(EBSPushController::class)->createBEAItem(224, $process_id, 4084, $value->digits_code, $value->upc_code, $value->supplier_item_code, $value->item_description, $value->brand, $value->warehouse_category, $value->current_srp, $value->purchase_price, $value->has_serial);
                        app(EBSPushController::class)->createBEAItem(225, $process_id, 4085, $value->digits_code, $value->upc_code, $value->supplier_item_code, $value->item_description, $value->brand, $value->warehouse_category, $value->current_srp, $value->purchase_price, $value->has_serial);
                        app(EBSPushController::class)->createBEAItem(263, $process_id, 8084, $value->digits_code, $value->upc_code, $value->supplier_item_code, $value->item_description, $value->brand, $value->warehouse_category, $value->current_srp, $value->purchase_price, $value->has_serial);
                        DB::commit();
                    } catch (\Exception $e) {
                        \Log::debug($e);
                        DB::rollback();
                    }
                    $cnt_success++;
                }
                
            }
        }
        \Log::info('manualbeachitemcreated: executed! '.$cnt_success.' items');
    }
    
    public function decrementItem($digits_code, $column, $qty) {
        return DB::table('items')
            ->where('digits_code', $digits_code)
            ->decrement($column, $qty);
    }
    
    public function decrementItemColumn($digits_code, $column, $qty) {
        DB::table('items')
            ->where('digits_code', $digits_code)
            ->decrement($column, $qty);
            
        return DB::table('items')
            ->where('digits_code', $digits_code)->first();
    }

    public function incrementItem($digits_code, $column, $qty) {
        return DB::table('items')
            ->where('digits_code', $digits_code)
            ->increment($column, $qty);
    }

    public function getItem($digits_code) {
        return DB::table('items')
            ->where('digits_code', $digits_code)
            ->first();
    }
}

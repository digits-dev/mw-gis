<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use CRUDBooster;

class POSPushController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $client;

    public function __construct()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        $this->client = new \SoapClient("http://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl", 
            array("location" => "http://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php")); 
    }

    public function index()
    {
        //
    }

    public function postSI($dr_number, $wh='DIGITSWAREHOUSE')
    {
        $items = app(EBSPullController::class)->getProductsByDRNumber($dr_number);
        $posItemDetails = array();

        foreach ($items as $key => $value) {
            
            $itemDetail = app(POSPullController::class)->getProduct($value->ordered_item);
            $beaItemDetail = app(EBSPullController::class)->getProductSerials($value->ordered_item, $dr_number);
            $serials = app(EBSPullController::class)->getItemSerials($value->ordered_item, $dr_number);
            $unique_serials = app(EBSPullController::class)->getDuplicateSerials($value->ordered_item, $dr_number);
            
            if($itemDetail['data']['record']['fproduct_type'] == 1){ //serialized
                //check if bea transactions have serial
                
                if(empty($beaItemDetail->serial))
                    CRUDBooster::redirect(CRUDBooster::mainpath(),'Error! Item code: '.$value->ordered_item.' don\'t have a serial number!','danger')->send();
                
                elseif(count($serials) == ($beaItemDetail->shipped_quantity)) {
                    // $serials = explode(",",$beaItemDetail->serial);
                    // $unique_serials = array_unique($serials);
                    
                    // if(count((array)$unique_serials) != $beaItemDetail->shipped_quantity){
                    if(count($unique_serials) !=0){
                        CRUDBooster::redirect(CRUDBooster::mainpath(),'Item code: '.$value->ordered_item.' with DR#: '.$dr_number.' have duplicate serial numbers!','danger')->send();
                    }
    
                    foreach ($serials as $key_serial => $value_serial) {
                        $posItemDetails[$key_serial.'-'.$value_serial->serial_number] = [
                            'item_code' => $value->ordered_item, 
                            'quantity' => 1,
                            'serial_number' => $value_serial->serial_number, //$value_serial
                            'item_price' => $itemDetail['data']['record']['flist_price']
                        ];
                    }
                    //CRUDBooster::redirect(CRUDBooster::mainpath(),'Item code: '.$value.' with DR#: '.$dr_number.' is serialized item!','success')->send();
                }
                    
                else
                    CRUDBooster::redirect(CRUDBooster::mainpath(),'Item code: '.$value->ordered_item.' with DR#: '.$dr_number.' serial numbers mismatched!','danger')->send();
            }
    
            else{ //general item
    
                if(!empty($beaItemDetail->serial)){
                    CRUDBooster::redirect(CRUDBooster::mainpath(),'Error! Item code: '.$value->ordered_item.' with DR#: '.$dr_number.' tagged as general item but with a serial number!','danger')->send();
                }
    
                //create stock adjustment
                $posItemDetails[$key+100] = [
                    'item_code' => $value->ordered_item, 
                    'quantity' => $beaItemDetail->shipped_quantity, 
                    'item_price' => $itemDetail['data']['record']['flist_price']
                ];
    
                //CRUDBooster::redirect(CRUDBooster::mainpath(),'Item code: '.$value->ordered_item.' with DR#: '.$dr_number.' is general item!','success')->send();
            }
        }
        
        $stockAdjustment = self::posCreateStockAdjustment($dr_number,$wh, $posItemDetails);
        \Log::info('dr create SI: '.json_encode($stockAdjustment));
        if($stockAdjustment['return_code'] == 0){

            DB::table('ebs_pull')->where('dr_number', $dr_number)->update([
                'si_document_number' => $stockAdjustment['data']['record']['fdocument_no']
            ]);
            //CRUDBooster::redirect(CRUDBooster::mainpath(),'DR#: '.$dr_number.' stock adjustment successful!','success')->send();
        }
        //return $stockAdjustment;
    }

    public function postST($dr_number, $from_warehouse, $to_warehouse)
    {
        
        $items = app(EBSPullController::class)->getProductsByDRNumber($dr_number);
        $posItemDetails = array();

        foreach ($items as $key => $value) {
            
            $itemDetail = app(POSPullController::class)->getProduct($value->ordered_item);

            while($itemDetail['return_code'] != 0){
                $itemDetail = app(POSPullController::class)->getProduct($value->ordered_item);
            }
            
            $beaItemDetail = app(EBSPullController::class)->getProductSerials($value->ordered_item, $dr_number);
            $serials = app(EBSPullController::class)->getItemSerials($value->ordered_item, $dr_number);
            $unique_serials = app(EBSPullController::class)->getDuplicateSerials($value->ordered_item, $dr_number);
            
            if($itemDetail['data']['record']['fproduct_type'] == 1){ //serialized
                //check if bea transactions have serial
                
                if(empty($beaItemDetail->serial)){
                    DB::table('ebs_pull')->where('dr_number', $dr_number)->update([
                        'status' => 'FAILED'
                    ]);
                    CRUDBooster::redirect(CRUDBooster::mainpath(),'Error! Item code: '.$value->ordered_item.' don\'t have a serial number!','danger')->send();
                }
                
                // elseif(substr_count($beaItemDetail->serial, ",") == ($beaItemDetail->shipped_quantity - 1)) {
                elseif(count($serials) == ($beaItemDetail->shipped_quantity)) {
                    // $serials = explode(",",$beaItemDetail->serial);
                    // $unique_serials = array_unique($serials);
                    
                    // if(count((array)$unique_serials) != $beaItemDetail->shipped_quantity){
                    if(count($unique_serials) !=0){
                        DB::table('ebs_pull')->where('dr_number', $dr_number)->update([
                            'status' => 'FAILED'
                        ]);
                        CRUDBooster::redirect(CRUDBooster::mainpath(),'Item code: '.$value->ordered_item.' with DR#: '.$dr_number.' have duplicate serial numbers!','danger')->send();
                    }
    
                    foreach ($serials as $key_serial => $value_serial) {
                        $posItemDetails[$key_serial.'-'.$value_serial->serial_number] = [
                            'item_code' => $value->ordered_item, 
                            'quantity' => 1,
                            'serial_number' => $value_serial->serial_number, //$value_serial
                            'item_price' => $itemDetail['data']['record']['flist_price']
                        ];
                    }
                }
                    
                else{
                    DB::table('ebs_pull')->where('dr_number', $dr_number)->update([
                        'status' => 'FAILED'
                    ]);
                    CRUDBooster::redirect(CRUDBooster::mainpath(),'Item code: '.$value->ordered_item.' with DR#: '.$dr_number.' serial numbers mismatched!','danger')->send();
                }
                    
            }
    
            else{ //general item
    
                if(!empty($beaItemDetail->serial)){
                    DB::table('ebs_pull')->where('dr_number', $dr_number)->update([
                        'status' => 'FAILED'
                    ]);
                    CRUDBooster::redirect(CRUDBooster::mainpath(),'Error! Item code: '.$value->ordered_item.' with DR#: '.$dr_number.' is general item but have a serial number!','danger')->send();
                }
    
                //create stock adjustment
                $posItemDetails[$key+100] = [
                    'item_code' => $value->ordered_item, 
                    'quantity' => $beaItemDetail->shipped_quantity, 
                    'item_price' => $itemDetail['data']['record']['flist_price']
                ];
    
            }
        }
        
        $stockTransfer = self::posCreateStockTransfer($dr_number, 'DIGITSWAREHOUSE', $from_warehouse, $to_warehouse, 'DELIVERY', $posItemDetails);
        \Log::info('dr create ST: '.json_encode($stockTransfer));
        if($stockTransfer['return_code'] == 0){
            //update with document number
            
            DB::table('ebs_pull')->where('dr_number', $dr_number)->update([
                'st_document_number' => $stockTransfer['data']['record']['fdocument_no']
            ]);
            //CRUDBooster::redirect(CRUDBooster::mainpath(),'DR#: '.$dr_number.' stock transfer successful!','success')->send();
        }

        //return $stockTransfer;
    }

    public function posCreateStockAdjustment($reference_code, $warehouse, $posItemDetails)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17052282</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>
                    <record>
                        <fdoctype>0</fdoctype>
                        <freference_code>".$reference_code."</freference_code>
                        <ftrxdate>".date('Ymd')."</ftrxdate>
                        <fsiteid>".$warehouse."</fsiteid>
                        <fthirdparty_siteid/>
                        <fmemo/>
                        <fcreated_date>".date('YmdHis')."</fcreated_date>";
                
                foreach ($posItemDetails as $key => $item) {
                   
                    $parameter .="
                        <product>
                            <fproductid>".$item['item_code']."</fproductid>
                            <fqty>".$item['quantity']."</fqty>
                            <fuom>PCS</fuom>";

                    if(!empty($item['serial_number'])){
                        $parameter .="<flotno>".$item['serial_number']."</flotno>";
                    }

                    $parameter .="<fuomqty>1.000000</fuomqty>
                            <fextprice>".$item['item_price']."</fextprice>
                        </product>";
                }
                        
                $parameter .="</record>
                </data>

            </root>";
        \Log::warning($parameter);
        $result = $this->client->call("SAVE_STOCK_ADJUSTMENT",$parameter);

        return (json_decode(json_encode(simplexml_load_string($result)), true));
    }

    public function posCreateItemOld($posItemDetails)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17052282</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>";

                foreach ($posItemDetails as $key => $item) {
                    $parameter .="
                    <record>
                        <fproductid>".$item['digits_code']."</fproductid>
                        <fname>".$item['item_description']."</fname>
                        <factive_flag>1</factive_flag>
                        <fuomid>PCS</fuomid>
                        <ftax_type>0</ftax_type>
                        <flist_price>".$item['dtp_rf']."</flist_price>
                        <fstnd_cost>".$item['current_srp']."</fstnd_cost>
                        <fproduct_type>".$item['serial_code']."</fproduct_type>
                        <fbarcode1>".$item['upc_code']."</fproduct_type>
                    </record>";
                }
                
                $parameter .="
                </data>

            </root>";
        
        $result = $this->client->call("SAVE_PRODUCT",$parameter);

        return (json_decode(json_encode(simplexml_load_string($result)), true));
    }

    public function posCreateItem($digits_code, $upc_code, $item_description, $current_srp, $store_cost, $serial_code)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17052282</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>
                    <record>
                        <fthirdpartyid>".$digits_code."</fthirdpartyid>
                        <fproductid>".$digits_code."</fproductid>
                        <fname>".$item_description."</fname>
                        <factive_flag>1</factive_flag>
                        <ftax_type>0</ftax_type>
                        <fuomid>PCS</fuomid>
                        <fstnd_cost>".$store_cost."</fstnd_cost>
                        <flist_price>".$current_srp."</flist_price>
                        <fproduct_type>".$serial_code."</fproduct_type>
                        <fbarcode1>".$upc_code."</fbarcode1>
                    </record>
                </data>
            </root>";
            
        $result = $this->client->call("SAVE_PRODUCT",$parameter);

        return (json_decode(json_encode(simplexml_load_string($result)), true));
    }

    public function posCreateStockAdjustmentOut($reference_code, $warehouse, $posItemDetails)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17052282</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>
                    <record>
                        <fdoctype>9</fdoctype>
                        <freference_code>".$reference_code."</freference_code>
                        <ftrxdate>".date('Ymd')."</ftrxdate>
                        <fsiteid>".$warehouse."</fsiteid>
                        <fthirdparty_siteid/>
                        <fmemo/>
                        <fcreated_date>".date('YmdHis')."</fcreated_date>";
                
                foreach ($posItemDetails as $key => $item) {
                   
                    $parameter .="
                        <product>
                            <fproductid>".$item['item_code']."</fproductid>
                            <fthirdparty_productid/>
                            <fqty>".$item['quantity']."</fqty>
                            <fuom>PCS</fuom>";

                    if(!empty($item['serial_number'])){
                        $parameter .="<flotno>".$item['serial_number']."</flotno>";
                    }

                    $parameter .="<fuomqty>1.000000</fuomqty>
                            <fextprice>".$item['item_price']."</fextprice>
                        </product>";
                }
                        
                $parameter .="</record>
                </data>

            </root>";
        
        $result = $this->client->call("SAVE_STOCK_ADJUSTMENT",$parameter);

        return (json_decode(json_encode(simplexml_load_string($result)), true));
    }
    
    public function posCreateStockAdjustmentOutReceiving($reference_code, $warehouse, $dateReceived, $posItemDetails)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17052282</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>
                    <record>
                        <fdoctype>9</fdoctype>
                        <freference_code>".$reference_code."</freference_code>
                        <ftrxdate>".$dateReceived."</ftrxdate>
                        <fsiteid>".$warehouse."</fsiteid>
                        <fthirdparty_siteid/>
                        <fmemo/>
                        <fcreated_date>".date('YmdHis')."</fcreated_date>";
                
                foreach ($posItemDetails as $key => $item) {
                   
                    $parameter .="
                        <product>
                            <fproductid>".$item['item_code']."</fproductid>
                            <fthirdparty_productid/>
                            <fqty>".$item['quantity']."</fqty>
                            <fuom>PCS</fuom>";

                    if(!empty($item['serial_number'])){
                        $parameter .="<flotno>".$item['serial_number']."</flotno>";
                    }

                    $parameter .="<fuomqty>1.000000</fuomqty>
                            <fextprice>".$item['item_price']."</fextprice>
                        </product>";
                }
                        
                $parameter .="</record>
                </data>

            </root>";
        
        $result = $this->client->call("SAVE_STOCK_ADJUSTMENT",$parameter);

        return (json_decode(json_encode(simplexml_load_string($result)), true));
    }

    public function posCreateStockTransfer($reference_code, $branch, $from_warehouse, $to_warehouse, $memo, $posItemDetails) //$reference_code
    {
        
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17052282</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>
                    <record>
                        <freference_code>".$reference_code."</freference_code>
                        <ftrxdate>".date('Ymd')."</ftrxdate>
                        <fofficeid>".$branch."</fofficeid>
                        <fsiteid>".$from_warehouse."</fsiteid>
                        <fdst_siteid>".$to_warehouse."</fdst_siteid>
                        <fstatus_flag>0</fstatus_flag>
                        <fmemo>".$memo."</fmemo>
                        <fcreated_date>".date('YmdHis')."</fcreated_date>";

                    foreach ($posItemDetails as $key => $item) {
                
                        $parameter .="
                            <product>
                                <fproductid>".$item['item_code']."</fproductid>
                                <fqty>".$item['quantity']."</fqty>
                                <fuom>PCS</fuom>";
    
                        if(!empty($item['serial_number'])){
                            $parameter .="<flotno>".$item['serial_number']."</flotno>";
                        }
    
                        $parameter .="<fuomqty>1.000000</fuomqty>
                                <fextprice>".$item['item_price']."</fextprice>
                            </product>";
                    }
                        
                    $parameter .="</record>
                </data>

            </root>";
        \Log::warning($parameter);
        $result = $this->client->call("SAVE_STOCK_TRANSFER",$parameter);
        return (json_decode(json_encode(simplexml_load_string($result)), true));
    }
    
    public function posRerunCreateStockTransfer($reference_code, $branch, $from_warehouse, $to_warehouse, $memo, $date_created, $posItemDetails) //$reference_code
    {
        
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17052282</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>
                    <record>
                        <freference_code>".$reference_code."</freference_code>
                        <ftrxdate>".$date_created."</ftrxdate>
                        <fofficeid>".$branch."</fofficeid>
                        <fsiteid>".$from_warehouse."</fsiteid>
                        <fdst_siteid>".$to_warehouse."</fdst_siteid>
                        <fstatus_flag>0</fstatus_flag>
                        <fmemo>".$memo."</fmemo>
                        <fcreated_date>".date('YmdHis')."</fcreated_date>";

                    foreach ($posItemDetails as $key => $item) {
                
                        $parameter .="
                            <product>
                                <fproductid>".$item['item_code']."</fproductid>
                                <fqty>".$item['quantity']."</fqty>
                                <fuom>PCS</fuom>";
    
                        if(!empty($item['serial_number'])){
                            $parameter .="<flotno>".$item['serial_number']."</flotno>";
                        }
    
                        $parameter .="<fuomqty>1.000000</fuomqty>
                                <fextprice>".$item['item_price']."</fextprice>
                            </product>";
                    }
                        
                    $parameter .="</record>
                </data>

            </root>";
        \Log::warning($parameter);
        $result = $this->client->call("SAVE_STOCK_TRANSFER",$parameter);
        return (json_decode(json_encode(simplexml_load_string($result)), true));
    }
    
    public function posCreateStockTransferReceiving($reference_code, $branch, $from_warehouse, $to_warehouse, $memo, $date_received, $posItemDetails) 
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17052282</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>
                    <record>
                        <freference_code>".$reference_code."</freference_code>
                        <ftrxdate>".$date_received."</ftrxdate>
                        <fofficeid>".$branch."</fofficeid>
                        <fsiteid>".$from_warehouse."</fsiteid>
                        <fdst_siteid>".$to_warehouse."</fdst_siteid>
                        <fstatus_flag>0</fstatus_flag>
                        <fmemo>".$memo."</fmemo>
                        <fcreated_date>".date('YmdHis')."</fcreated_date>";

                    foreach ($posItemDetails as $key => $item) {
                
                        $parameter .="
                            <product>
                                <fproductid>".$item['item_code']."</fproductid>
                                <fqty>".$item['quantity']."</fqty>
                                <fuom>PCS</fuom>";
    
                        if(!empty($item['serial_number'])){
                            $parameter .="<flotno>".$item['serial_number']."</flotno>";
                        }
    
                        $parameter .="<fuomqty>1.000000</fuomqty>
                                <fextprice>".$item['item_price']."</fextprice>
                            </product>";
                    }
                        
                    $parameter .="</record>
                </data>

            </root>";
        \Log::info($parameter);
        $result = $this->client->call("SAVE_STOCK_TRANSFER",$parameter);
        return (json_decode(json_encode(simplexml_load_string($result)), true));
    }

    public function voidStockTransfer($reference_code)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>17052282</fw3p_id>
                    <fw3p_key>12345</fw3p_key>
                </id>

                <data>
                    <record>
                        <fdocument_no>".$reference_code."</fdocument_no>
                    </record>
                </data>

            </root>";
        
        $result = $this->client->call("VOID_STOCK_TRANSFER",$parameter);
        return (json_decode(json_encode(simplexml_load_string($result)), true));
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
}

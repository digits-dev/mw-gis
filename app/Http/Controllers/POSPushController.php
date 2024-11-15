<?php

namespace App\Http\Controllers;

use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SoapClient;

class POSPushController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $client;
    private $w3p_id;
    private $w3p_key;

    public function __construct()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        $this->client = new SoapClient("http://bc.alliancewebpos.com/appserv/app/w3p/w3p.wsdl", 
            array("location" => "http://bc.alliancewebpos.com/appserv/app/w3p/W3PSoapServer.php")); 
        
        $this->w3p_id = config('w3p.w3p_id');
        $this->w3p_key = config('w3p.w3p_key');
    }

    public function postSI($drNumber, $wh='DIGITSWAREHOUSE')
    {
        $ebsPull = new EBSPullController();
        $items = $ebsPull->getProductsByDRNumber($drNumber);
        $posItemDetails = [];

        foreach ($items as $key => $value) {
            $posPull = new POSPullController();
            $itemDetail = $posPull->getProduct($value->ordered_item);
            $beaItemDetail = $ebsPull->getProductSerials($value->ordered_item, $drNumber);
            $serials = $ebsPull->getItemSerials($value->ordered_item, $drNumber);
            $unique_serials = $ebsPull->getDuplicateSerials($value->ordered_item, $drNumber);
            
            if($itemDetail['data']['record']['fproduct_type'] == 1){ //serialized
                //check if bea transactions have serial
                
                if(empty($beaItemDetail->serial)) {
                    CRUDBooster::redirect(CRUDBooster::mainpath(),"Error! Item code: {$value->ordered_item} don't have a serial number!",'danger')->send();
                }
                
                elseif(count($serials) == ($beaItemDetail->shipped_quantity)) {
                    
                    if(count($unique_serials) !=0){
                        CRUDBooster::redirect(CRUDBooster::mainpath(),"Item code: {$value->ordered_item} with DR#: {$drNumber} have duplicate serial numbers!",'danger')->send();
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
                    
                else {
                    CRUDBooster::redirect(CRUDBooster::mainpath(),"Item code: {$value->ordered_item} with DR#: {$drNumber} serial numbers mismatched!",'danger')->send();
                }
            }
    
            else{ //general item
    
                if(!empty($beaItemDetail->serial)){
                    CRUDBooster::redirect(CRUDBooster::mainpath(),"Error! Item code: {$value->ordered_item} with DR#: {$drNumber} tagged as general item but with a serial number!",'danger')->send();
                }
    
                //create stock adjustment
                $posItemDetails[$key+100] = [
                    'item_code' => $value->ordered_item,
                    'quantity' => $beaItemDetail->shipped_quantity,
                    'item_price' => $itemDetail['data']['record']['flist_price']
                ];
    
                //CRUDBooster::redirect(CRUDBooster::mainpath(),'Item code: '.$value->ordered_item.' with DR#: '.$drNumber.' is general item!','success')->send();
            }
        }
        
        $stockAdjustment = self::posCreateStockAdjustment($drNumber, $wh, $posItemDetails);
        Log::info('dr create SI: '.json_encode($stockAdjustment));
        if($stockAdjustment['return_code'] == 0){

            DB::table('ebs_pull')->where('dr_number', $drNumber)->update([
                'si_document_number' => $stockAdjustment['data']['record']['fdocument_no']
            ]);
        }
    }

    public function postST($drNumber, $fromWarehouse, $toWarehouse)
    {
        $ebsPull = new EBSPullController();
        $items = $ebsPull->getProductsByDRNumber($drNumber);
        $posItemDetails = array();

        foreach ($items as $key => $value) {
            $posPull = new POSPullController();
            $itemDetail = $posPull->getProduct($value->ordered_item);

            while($itemDetail['return_code'] != 0){
                $itemDetail = $posPull->getProduct($value->ordered_item);
            }
            
            $beaItemDetail = $ebsPull->getProductSerials($value->ordered_item, $drNumber);
            $serials = $ebsPull->getItemSerials($value->ordered_item, $drNumber);
            $unique_serials = $ebsPull->getDuplicateSerials($value->ordered_item, $drNumber);
            
            if($itemDetail['data']['record']['fproduct_type'] == 1){ //serialized
                //check if bea transactions have serial
                
                if(empty($beaItemDetail->serial)){
                    DB::table('ebs_pull')->where('dr_number', $drNumber)->update([
                        'status' => 'FAILED'
                    ]);
                    CRUDBooster::redirect(CRUDBooster::mainpath(),"Error! Item code: {$value->ordered_item} don't have a serial number!",'danger')->send();
                }
                
                elseif(count($serials) == ($beaItemDetail->shipped_quantity)) {
                    
                    if(count($unique_serials) !=0){
                        DB::table('ebs_pull')->where('dr_number', $drNumber)->update([
                            'status' => 'FAILED'
                        ]);
                        CRUDBooster::redirect(CRUDBooster::mainpath(),"Item code: {$value->ordered_item} with DR#: {$drNumber} have duplicate serial numbers!",'danger')->send();
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
                    DB::table('ebs_pull')->where('dr_number', $drNumber)->update([
                        'status' => 'FAILED'
                    ]);
                    CRUDBooster::redirect(CRUDBooster::mainpath(),"Item code: {$value->ordered_item} with DR#: {$drNumber} serial numbers mismatched!",'danger')->send();
                }
                    
            }
    
            else{ //general item
    
                if(!empty($beaItemDetail->serial)){
                    DB::table('ebs_pull')->where('dr_number', $drNumber)->update([
                        'status' => 'FAILED'
                    ]);
                    CRUDBooster::redirect(CRUDBooster::mainpath(),"Error! Item code: {$value->ordered_item} with DR#: {$drNumber} is general item but have a serial number!",'danger')->send();
                }
    
                //create stock adjustment
                $posItemDetails[$key+100] = [
                    'item_code' => $value->ordered_item,
                    'quantity' => $beaItemDetail->shipped_quantity,
                    'item_price' => $itemDetail['data']['record']['flist_price']
                ];
    
            }
        }
        
        $stockTransfer = self::posCreateStockTransfer($drNumber, 'DIGITSWAREHOUSE', $fromWarehouse, $toWarehouse, 'DELIVERY', $posItemDetails);
        Log::info('dr create ST: '.json_encode($stockTransfer));
        if($stockTransfer['return_code'] == 0){
            //update with document number
            
            DB::table('ebs_pull')->where('dr_number', $drNumber)->update([
                'st_document_number' => $stockTransfer['data']['record']['fdocument_no']
            ]);
        }

    }

    public function posCreateStockAdjustment($referenceCode, $warehouse, $posItemDetails)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>{$this->w3p_id}</fw3p_id>
                    <fw3p_key>{$this->w3p_key}</fw3p_key>
                </id>

                <data>
                    <record>
                        <fdoctype>0</fdoctype>
                        <freference_code>{$referenceCode}</freference_code>
                        <ftrxdate>{date('Ymd')}</ftrxdate>
                        <fsiteid>{$warehouse}</fsiteid>
                        <fthirdparty_siteid/>
                        <fmemo/>
                        <fcreated_date>{date('YmdHis')}</fcreated_date>";
                
                foreach ($posItemDetails as $item) {
                   
                    $parameter .="
                        <product>
                            <fproductid>{$item['item_code']}</fproductid>
                            <fqty>{$item['quantity']}</fqty>
                            <fuom>PCS</fuom>";

                    if(!empty($item['serial_number'])){
                        $parameter .="<flotno>{$item['serial_number']}</flotno>";
                    }

                    $parameter .="<fuomqty>1.000000</fuomqty>
                            <fextprice>{$item['item_price']}</fextprice>
                        </product>";
                }
                        
                $parameter .="</record>
                </data>

            </root>";

        Log::warning($parameter);
        $result = $this->client->call("SAVE_STOCK_ADJUSTMENT",$parameter);

        return json_decode(json_encode(simplexml_load_string($result)), true);
    }

    public function posCreateItemOld($posItemDetails)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>{$this->w3p_id}</fw3p_id>
                    <fw3p_key>{$this->w3p_key}</fw3p_key>
                </id>

                <data>";

                foreach ($posItemDetails as $item) {
                    $parameter .="
                    <record>
                        <fproductid>{$item['digits_code']}</fproductid>
                        <fname>{$item['item_description']}</fname>
                        <factive_flag>1</factive_flag>
                        <fuomid>PCS</fuomid>
                        <ftax_type>0</ftax_type>
                        <flist_price>{$item['dtp_rf']}</flist_price>
                        <fstnd_cost>{$item['current_srp']}</fstnd_cost>
                        <fproduct_type>{$item['serial_code']}</fproduct_type>
                        <fbarcode1>{$item['upc_code']}</fproduct_type>
                    </record>";
                }
                
                $parameter .="
                </data>

            </root>";
        
        $result = $this->client->call("SAVE_PRODUCT",$parameter);

        return json_decode(json_encode(simplexml_load_string($result)), true);
    }

    public function posCreateItem($digitsCode, $upcCode, $itemDescription, $currentSrp, $storeCost, $serialCode)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>{$this->w3p_id}</fw3p_id>
                    <fw3p_key>{$this->w3p_key}</fw3p_key>
                </id>

                <data>
                    <record>
                        <fthirdpartyid>{$digitsCode}</fthirdpartyid>
                        <fproductid>{$digitsCode}</fproductid>
                        <fname>{$itemDescription}</fname>
                        <factive_flag>1</factive_flag>
                        <ftax_type>0</ftax_type>
                        <fuomid>PCS</fuomid>
                        <fstnd_cost>{$storeCost}</fstnd_cost>
                        <flist_price>{$currentSrp}</flist_price>
                        <fproduct_type>{$serialCode}</fproduct_type>
                        <fbarcode1>{$upcCode}</fbarcode1>
                    </record>
                </data>
            </root>";
            
        $result = $this->client->call("SAVE_PRODUCT",$parameter);

        return json_decode(json_encode(simplexml_load_string($result)), true);
    }

    public function posCreateStockAdjustmentOut($referenceCode, $warehouse, $posItemDetails)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>{$this->w3p_id}</fw3p_id>
                    <fw3p_key>{$this->w3p_key}</fw3p_key>
                </id>

                <data>
                    <record>
                        <fdoctype>9</fdoctype>
                        <freference_code>{$referenceCode}</freference_code>
                        <ftrxdate>{date('Ymd')}</ftrxdate>
                        <fsiteid>{$warehouse}</fsiteid>
                        <fthirdparty_siteid/>
                        <fmemo/>
                        <fcreated_date>{date('YmdHis')}</fcreated_date>";
                
                foreach ($posItemDetails as $item) {
                   
                    $parameter .="
                        <product>
                            <fproductid>{$item['item_code']}</fproductid>
                            <fthirdparty_productid/>
                            <fqty>{$item['quantity']}</fqty>
                            <fuom>PCS</fuom>";

                    if(!empty($item['serial_number'])){
                        $parameter .="<flotno>{$item['serial_number']}</flotno>";
                    }

                    $parameter .="<fuomqty>1.000000</fuomqty>
                            <fextprice>{$item['item_price']}</fextprice>
                        </product>";
                }
                        
                $parameter .="</record>
                </data>

            </root>";
        
        $result = $this->client->call("SAVE_STOCK_ADJUSTMENT",$parameter);

        return json_decode(json_encode(simplexml_load_string($result)), true);
    }
    
    public function posCreateStockAdjustmentOutReceiving($referenceCode, $warehouse, $dateReceived, $posItemDetails)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>{$this->w3p_id}</fw3p_id>
                    <fw3p_key>{$this->w3p_key}</fw3p_key>
                </id>

                <data>
                    <record>
                        <fdoctype>9</fdoctype>
                        <freference_code>{$referenceCode}</freference_code>
                        <ftrxdate>{$dateReceived}</ftrxdate>
                        <fsiteid>{$warehouse}</fsiteid>
                        <fthirdparty_siteid/>
                        <fmemo/>
                        <fcreated_date>{date('YmdHis')}</fcreated_date>";
                
                foreach ($posItemDetails as $item) {
                   
                    $parameter .="
                        <product>
                            <fproductid>{$item['item_code']}</fproductid>
                            <fthirdparty_productid/>
                            <fqty>{$item['quantity']}</fqty>
                            <fuom>PCS</fuom>";

                    if(!empty($item['serial_number'])){
                        $parameter .="<flotno>{$item['serial_number']}</flotno>";
                    }

                    $parameter .="<fuomqty>1.000000</fuomqty>
                            <fextprice>{$item['item_price']}</fextprice>
                        </product>";
                }
                        
                $parameter .="</record>
                </data>

            </root>";
        
        $result = $this->client->call("SAVE_STOCK_ADJUSTMENT",$parameter);

        return json_decode(json_encode(simplexml_load_string($result)), true);
    }

    public function posCreateStockTransfer($referenceCode, $branch, $fromWarehouse, $toWarehouse, $memo, $posItemDetails) //$referenceCode
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>{$this->w3p_id}</fw3p_id>
                    <fw3p_key>{$this->w3p_key}</fw3p_key>
                </id>

                <data>
                    <record>
                        <freference_code>{$referenceCode}</freference_code>
                        <ftrxdate>{date('Ymd')}</ftrxdate>
                        <fofficeid>{$branch}</fofficeid>
                        <fsiteid>{$fromWarehouse}</fsiteid>
                        <fdst_siteid>{$toWarehouse}</fdst_siteid>
                        <fstatus_flag>0</fstatus_flag>
                        <fmemo>{$memo}</fmemo>
                        <fcreated_date>{date('YmdHis')}</fcreated_date>";

                    foreach ($posItemDetails as $item) {
                
                        $parameter .="
                            <product>
                                <fproductid>{$item['item_code']}</fproductid>
                                <fqty>{$item['quantity']}</fqty>
                                <fuom>PCS</fuom>";
    
                        if(!empty($item['serial_number'])){
                            $parameter .="<flotno>{$item['serial_number']}</flotno>";
                        }
    
                        $parameter .="<fuomqty>1.000000</fuomqty>
                                <fextprice>{$item['item_price']}</fextprice>
                            </product>";
                    }
                        
                    $parameter .="</record>
                </data>

            </root>";

        Log::warning($parameter);
        $result = $this->client->call("SAVE_STOCK_TRANSFER",$parameter);

        return json_decode(json_encode(simplexml_load_string($result)), true);
    }
    
    public function posRerunCreateStockTransfer($referenceCode, $branch, $fromWarehouse, $toWarehouse, $memo, $dateCreated, $posItemDetails) //$referenceCode
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>{$this->w3p_id}</fw3p_id>
                    <fw3p_key>{$this->w3p_key}</fw3p_key>
                </id>

                <data>
                    <record>
                        <freference_code>{$referenceCode}</freference_code>
                        <ftrxdate>{$dateCreated}</ftrxdate>
                        <fofficeid>{$branch}</fofficeid>
                        <fsiteid>{$fromWarehouse}</fsiteid>
                        <fdst_siteid>{$toWarehouse}</fdst_siteid>
                        <fstatus_flag>0</fstatus_flag>
                        <fmemo>{$memo}</fmemo>
                        <fcreated_date>{date('YmdHis')}</fcreated_date>";

                    foreach ($posItemDetails as $item) {
                
                        $parameter .="
                            <product>
                                <fproductid>{$item['item_code']}</fproductid>
                                <fqty>{$item['quantity']}</fqty>
                                <fuom>PCS</fuom>";
    
                        if(!empty($item['serial_number'])){
                            $parameter .="<flotno>{$item['serial_number']}</flotno>";
                        }
    
                        $parameter .="<fuomqty>1.000000</fuomqty>
                                <fextprice>{$item['item_price']}</fextprice>
                            </product>";
                    }
                        
                    $parameter .="</record>
                </data>

            </root>";

        Log::warning($parameter);
        $result = $this->client->call("SAVE_STOCK_TRANSFER",$parameter);

        return json_decode(json_encode(simplexml_load_string($result)), true);
    }
    
    public function posCreateStockTransferReceiving($referenceCode, $branch, $fromWarehouse, $toWarehouse, $memo, $dataReceived, $posItemDetails)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>{$this->w3p_id}</fw3p_id>
                    <fw3p_key>{$this->w3p_key}</fw3p_key>
                </id>

                <data>
                    <record>
                        <freference_code>{$referenceCode}</freference_code>
                        <ftrxdate>{$dataReceived}</ftrxdate>
                        <fofficeid>{$branch}</fofficeid>
                        <fsiteid>{$fromWarehouse}</fsiteid>
                        <fdst_siteid>{$toWarehouse}</fdst_siteid>
                        <fstatus_flag>0</fstatus_flag>
                        <fmemo>{$memo}</fmemo>
                        <fcreated_date>{date('YmdHis')}</fcreated_date>";

                    foreach ($posItemDetails as $item) {
                
                        $parameter .="
                            <product>
                                <fproductid>{$item['item_code']}</fproductid>
                                <fqty>{$item['quantity']}</fqty>
                                <fuom>PCS</fuom>";
    
                        if(!empty($item['serial_number'])){
                            $parameter .="<flotno>{$item['serial_number']}</flotno>";
                        }
    
                        $parameter .="<fuomqty>1.000000</fuomqty>
                                <fextprice>{$item['item_price']}</fextprice>
                            </product>";
                    }
                        
                    $parameter .="</record>
                </data>

            </root>";

        Log::info($parameter);
        $result = $this->client->call("SAVE_STOCK_TRANSFER",$parameter);

        return json_decode(json_encode(simplexml_load_string($result)), true);
    }

    public function voidStockTransfer($referenceCode)
    {
        $parameter = "
            <root>
                <id>
                    <fw3p_id>{$this->w3p_id}</fw3p_id>
                    <fw3p_key>{$this->w3p_key}</fw3p_key>
                </id>

                <data>
                    <record>
                        <fdocument_no>{$referenceCode}</fdocument_no>
                    </record>
                </data>

            </root>";
        
        $result = $this->client->call("VOID_STOCK_TRANSFER",$parameter);

        return json_decode(json_encode(simplexml_load_string($result)), true);
    }
}

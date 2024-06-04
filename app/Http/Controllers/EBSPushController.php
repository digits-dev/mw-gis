<?php

namespace App\Http\Controllers;

use App\OracleItemInterface;
use Illuminate\Http\Request;
use DB;
use PDO;
use CRUDBooster;

class EBSPushController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $header_next_val;
    private $sor_header_next_val;
    private $groups_next_val;
    private $sysdate;
    private $transaction_next_val;
    private $rcv_next_val;
    private $groups_curr_val;

    public function __construct()
    {
        $this->header_next_val = DB::connection('oracle')->select(DB::raw('select RCV_HEADERS_INTERFACE_S.nextval from dual'));
        $this->sysdate = DB::connection('oracle')->select(DB::raw('select SYSDATE from dual'));

        $this->transaction_next_val = DB::connection('oracle')->select(DB::raw('select RCV_TRANSACTIONS_INTERFACE_S.NEXTVAL from dual'));
        $this->rcv_next_val = $this->header_next_val;
    }
    
    public function index()
    {
        //
    }

    public function closeTrip($p_delivery_id)
    {
        $pdo = DB::connection('oracle')->getPdo();

        $stmt = $pdo->prepare("begin CLOSE_TRIP_BPG(:p_delivery_id); end;");
        $stmt->bindParam(':p_delivery_id', $p_delivery_id, PDO::PARAM_INT);
        $stmt->execute();

        //CRUDBooster::redirect(CRUDBooster::mainpath(),'DR# '.$p_delivery_id.' has been closed successfully!','success')->send();
        
    }

    public function acceptedDate($p_delivery_id)
    {
        $pdo = DB::connection('oracle')->getPdo();

        $stmt = $pdo->prepare("begin ACCEPTED_DATE_BPG(:p_delivery_id); end;");
        $stmt->bindParam(':p_delivery_id', $p_delivery_id, PDO::PARAM_INT);
        $stmt->execute();
        
        //CRUDBooster::redirect(CRUDBooster::mainpath(),'DR# '.$p_delivery_id.' has been updated successfully!','success')->send();
        
    }

    public function dooReceiving($dr_number)
    {

        $this->header_next_val = DB::connection('oracle')->select(DB::raw('select RCV_HEADERS_INTERFACE_S.nextval from dual'));
        $this->groups_next_val = DB::connection('oracle')->select(DB::raw('select RCV_INTERFACE_GROUPS_S.NEXTVAL from dual'));
        $this->groups_curr_val = $this->groups_next_val;
        
        self::insertRcvHeadersInterface($dr_number);
        $shipmentHeader = self::getShipmentHeaders($dr_number);
        $shipmentLines = self::getShipmentLines($shipmentHeader);

        foreach ($shipmentLines as $key => $value) {

            $this->sysdate = DB::connection('oracle')->select(DB::raw('select SYSDATE from dual'));
            $this->transaction_next_val = DB::connection('oracle')->select(DB::raw('select RCV_TRANSACTIONS_INTERFACE_S.NEXTVAL from dual'));
            $this->rcv_next_val = $this->header_next_val;
            
            self::insertRcvTransactionInterface($dr_number, 
                $value->item_id, 
                $value->quantity_shipped, 
                $value->to_subinventory, 
                $shipmentHeader, 
                $value->shipment_line_id);
        }
        
        //CRUDBooster::redirect(CRUDBooster::mainpath(),'DR# '.$dr_number.' has been received successfully!','success')->send();
    }
    
    public function pushdooReceivingInterface($dr_number, $item_id)
    {

        $this->header_next_val = DB::connection('oracle')->select(DB::raw('select RCV_HEADERS_INTERFACE_S.nextval from dual'));
        $this->groups_next_val = DB::connection('oracle')->select(DB::raw('select RCV_INTERFACE_GROUPS_S.NEXTVAL from dual'));
        $this->groups_curr_val = $this->groups_next_val;
        
        self::insertRcvHeadersInterface($dr_number);
        $shipmentHeader = self::getShipmentHeadersDOTR($dr_number);
        $shipmentLines = self::getShipmentLines($shipmentHeader);

        foreach ($shipmentLines as $key => $value) {
            if($item_id == $value->item_id) {
                $this->sysdate = DB::connection('oracle')->select(DB::raw('select SYSDATE from dual'));
                $this->transaction_next_val = DB::connection('oracle')->select(DB::raw('select RCV_TRANSACTIONS_INTERFACE_S.NEXTVAL from dual'));
                $this->rcv_next_val = $this->header_next_val;
                
                self::insertRcvTransactionInterface($dr_number, 
                    $value->item_id, 
                    $value->quantity_shipped, 
                    $value->to_subinventory, 
                    $shipmentHeader, 
                    $value->shipment_line_id);
            }
        }
        
        //CRUDBooster::redirect(CRUDBooster::mainpath(),'DR# '.$dr_number.' has been received successfully!','success')->send();
    }

    public function insertRcvHeadersInterface($dr_number)
    {
        
        $result = DB::connection('oracle')->table('RCV_HEADERS_INTERFACE')->insert([
            'HEADER_INTERFACE_ID' => $this->header_next_val[0]->nextval,
            'GROUP_ID' => $this->groups_next_val[0]->nextval,
            'PROCESSING_STATUS_CODE' => 'PENDING',
            'RECEIPT_SOURCE_CODE' => 'INVENTORY',
            'TRANSACTION_TYPE' => 'NEW',
            'AUTO_TRANSACT_CODE' => 'DELIVER',
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'LAST_UPDATE_LOGIN' => 0,
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'CREATED_BY' => 0,
            'SHIPMENT_NUM' => $dr_number,
            'SHIP_TO_ORGANIZATION_ID' => 223,
            'EXPECTED_RECEIPT_DATE' => $this->sysdate[0]->sysdate,
            'VALIDATION_FLAG' => 'Y'
        ]);
    }

    public function getShipmentHeaders($dr_number)
    {
        return DB::connection('oracle')->table('RCV_SHIPMENT_HEADERS')->where('SHIPMENT_NUM', $dr_number)->value('SHIPMENT_HEADER_ID');
    }
    
    public function getShipmentHeadersDOTR($dr_number)
    {
        return DB::connection('oracle')->table('RCV_SHIPMENT_HEADERS')
            ->where('SHIPMENT_NUM', $dr_number)
            ->orderBy('SHIPMENT_HEADER_ID','DESC')
            ->limit(1)
            ->value('SHIPMENT_HEADER_ID');
    }

    public function insertRcvTransactionInterface($dr_number, $item_id, $quantity, $branch, $shipment_header_id, $shipment_line_id)
    {

        $result = DB::connection('oracle')->table('RCV_TRANSACTIONS_INTERFACE')->insert([
            'INTERFACE_TRANSACTION_ID' => $this->transaction_next_val[0]->nextval,
            'GROUP_ID' => $this->groups_curr_val[0]->nextval,
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'CREATED_BY' => 0,
            'LAST_UPDATE_LOGIN' => 0,
            'TRANSACTION_TYPE' => 'RECEIVE',
            'TRANSACTION_DATE' => $this->sysdate[0]->sysdate,
            'PROCESSING_STATUS_CODE' => 'PENDING',
            'PROCESSING_MODE_CODE' => 'BATCH',
            'TRANSACTION_STATUS_CODE' => 'PENDING',
            'QUANTITY' => $quantity,
            'UNIT_OF_MEASURE' => 'PIECE',
            'INTERFACE_SOURCE_CODE' => 'RCV',
            'ITEM_ID' => $item_id,
            'EMPLOYEE_ID' => 0,
            'AUTO_TRANSACT_CODE' => 'DELIVER',
            'SHIPMENT_HEADER_ID' => $shipment_header_id,
            'SHIPMENT_LINE_ID' => $shipment_line_id,
            'RECEIPT_SOURCE_CODE' => 'INVENTORY',
            'TO_ORGANIZATION_ID' => 223,
            'SOURCE_DOCUMENT_CODE' => 'INVENTORY',
            'DESTINATION_TYPE_CODE' => 'INVENTORY',
            'SUBINVENTORY' => $branch,
            'SHIPMENT_NUM' => $dr_number,
            'EXPECTED_RECEIPT_DATE' => $this->sysdate[0]->sysdate,
            'HEADER_INTERFACE_ID' => $this->rcv_next_val[0]->nextval,
            'VALIDATION_FLAG' => 'Y'
            
        ]);
        
    }

    public function getShipmentLines($shipment_header_id)
    {
        return DB::connection('oracle')->table('RCV_SHIPMENT_LINES')->where('SHIPMENT_HEADER_ID', $shipment_header_id)
            ->select('SHIPMENT_LINE_ID','ITEM_ID','QUANTITY_SHIPPED','TO_SUBINVENTORY')->get();
    }

    public function createDOT($dr_number, $item_id, $quantity, $transfer_subinventory, $locator_id, $org_id='224')
    {
        $result = DB::connection('oracle')->table('MTL_TRANSACTIONS_INTERFACE')->insert([       
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'CREATED_BY' => 0,
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'SOURCE_CODE' => 'BEAPOSMW',
            'SOURCE_LINE_ID' => 1,
            'SOURCE_HEADER_ID' => 1,
            'PROCESS_FLAG' => 1,
            'INVENTORY_ITEM_ID' => $item_id,
            'ORGANIZATION_ID' => $org_id,
            'SUBINVENTORY_CODE' => 'STAGINGMO',
            'TRANSACTION_TYPE_ID' => 21,
            'TRANSACTION_QUANTITY' => ($quantity)*(-1),
            'TRANSACTION_UOM' => 'Pc',
            'TRANSACTION_DATE' => $this->sysdate[0]->sysdate,
            'TRANSFER_ORGANIZATION' => 223,
            'TRANSFER_SUBINVENTORY' => $transfer_subinventory,
            'TRANSACTION_MODE' => 3,
            'REVISION' => '',
            'SHIPMENT_NUMBER' => $dr_number,
            'LOCATOR_ID' => $locator_id        
        ]);
    }

    public function receivingTransaction()
    {
        $pdo = DB::connection('oracle')->getPdo(); //->statement('execute RECEIVING_TRANSACTION_BPG');

        $stmt = $pdo->prepare("begin RECEIVING_TRANSACTION_BPG(); end;");
        $stmt->execute();
    }

    public function processTransactionInterface()
    {
        // DB::connection('oracle')->beginTransaction();
        $pdo = DB::connection('oracle')->getPdo(); 

        $stmt = $pdo->prepare("begin PROCESS_TRANSACTION_BPG(); end;"); //begin PROCESS_TRANSACTION_BPG; end; 
        $stmt->execute();
        // DB::connection('oracle')->commit();
    }

    public function processOrderImport()
    {
        $pdo = DB::connection('oracle')->getPdo();

        $stmt = $pdo->prepare("begin PROCESS_ORDER_IMPORT_BPG(); end;");
        $stmt->execute();
    }

    public function sorHeaders($price_list_id, $ship_from_org, $sold_to_org, $ship_to_org, $invoice_to_org, $dr_number)
    {
        $data = array();

        $this->sor_header_next_val = DB::connection('oracle')->select(DB::raw('select OE_ORDER_HEADERS_S.nextval from dual'));
        $data['sor_header'] = $this->sor_header_next_val[0]->nextval;

        $result = DB::connection('oracle')->table('OE_HEADERS_IFACE_ALL')->insert([
            'ORDER_SOURCE_ID' => 1001, //need to update when BEA PROD
            'ORIG_SYS_DOCUMENT_REF' => $this->sor_header_next_val[0]->nextval,
            'ORG_ID' => 81,
            'SOLD_FROM_ORG_ID' => 81,
            'SHIP_FROM_ORG_ID' => $ship_from_org,
            'ORDERED_DATE' => $this->sysdate[0]->sysdate,
            'ORDER_TYPE_ID' => 1023,
            'SOLD_TO_ORG_ID' => $sold_to_org,
            'PAYMENT_TERM_ID' => 4, //need to check
            'OPERATION_CODE' => 'CREATE',
            'CREATED_BY' => 0,
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'CUSTOMER_PO_NUMBER' => $dr_number,
            'PRICE_LIST_ID' => $price_list_id,
            'CONTEXT' => 'MIDDLEWARE',
            'ATTRIBUTE19' => '',
            'SHIP_TO_ORG_ID' => $ship_to_org,
            'INVOICE_TO_ORG_ID' => $invoice_to_org,
            'BOOKED_FLAG' => 'Y'
        ]);
        return $data;
    }
    
    public function sorLines($line_number, $orig_sys_document_ref, $item_id, $quantity, $price_list, $unit_selling_price, $unit_list_price, $return_reason, $ship_from_org, $subinventory)
    {
        $lines_nextval = DB::connection('oracle')->select(DB::raw('select OE_ORDER_LINES_S.nextval from dual'));

        $result = DB::connection('oracle')->table('OE_LINES_IFACE_ALL')->insert([
            'ORDER_SOURCE_ID' => 1001, //need to update when BEA PROD
            'ORIG_SYS_DOCUMENT_REF' => $orig_sys_document_ref,
            'ORIG_SYS_LINE_REF' => $lines_nextval[0]->nextval,
            'LINE_NUMBER' => $line_number,
            'INVENTORY_ITEM_ID' => $item_id,
            'ORDERED_QUANTITY' => $quantity,
            'SHIP_FROM_ORG_ID' => $ship_from_org,
            'ORG_ID' => 81,
            'PRICING_QUANTITY' => $quantity, // need to check
            'UNIT_SELLING_PRICE' => $unit_selling_price,
            'UNIT_LIST_PRICE' => $unit_list_price,
            'PRICE_LIST_ID' => $price_list,
            'PAYMENT_TERM_ID' => 4, // need to check
            'SCHEDULE_SHIP_DATE' => $this->sysdate[0]->sysdate,
            'REQUEST_DATE' => $this->sysdate[0]->sysdate,
            'CREATED_BY' => 0,
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'LINE_TYPE_ID' => 1003,
            'CALCULATE_PRICE_FLAG' => 'N',
            'RETURN_REASON_CODE' => $return_reason,
            'SUBINVENTORY' => $subinventory
        ]);
    }

    //online
    public function sorOnlineHeaders($price_list_id, $ship_from_org, $sold_to_org, $ship_to_org, $invoice_to_org, $dr_number)
    {
        $data = array();

        $this->sor_header_next_val = DB::connection('oracle')->select(DB::raw('select OE_ORDER_HEADERS_S.nextval from dual'));
        $data['sor_header'] = $this->sor_header_next_val[0]->nextval;

        $result = DB::connection('oracle')->table('OE_HEADERS_IFACE_ALL')->insert([
            'ORDER_SOURCE_ID' => 1001, //need to update when BEA PROD
            'ORIG_SYS_DOCUMENT_REF' => $this->sor_header_next_val[0]->nextval,
            'ORG_ID' => 81,
            'SOLD_FROM_ORG_ID' => 81,
            'SHIP_FROM_ORG_ID' => $ship_from_org,
            'ORDERED_DATE' => $this->sysdate[0]->sysdate,
            'ORDER_TYPE_ID' => 1170, //need to update when BEA PROD
            'SOLD_TO_ORG_ID' => $sold_to_org,
            'PAYMENT_TERM_ID' => 4, //need to check
            'OPERATION_CODE' => 'CREATE',
            'CREATED_BY' => 0,
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'CUSTOMER_PO_NUMBER' => $dr_number,
            'PRICE_LIST_ID' => $price_list_id,
            'CONTEXT' => 'MIDDLEWARE',
            'ATTRIBUTE19' => '',
            'SHIP_TO_ORG_ID' => $ship_to_org,
            'INVOICE_TO_ORG_ID' => $invoice_to_org,
            'BOOKED_FLAG' => 'Y'
        ]);
        return $data;
    }
    
    public function sorOnlineLines($line_number, $orig_sys_document_ref, $item_id, $quantity, $price_list, $unit_selling_price, $unit_list_price, $return_reason, $ship_from_org, $subinventory)
    {
        $lines_nextval = DB::connection('oracle')->select(DB::raw('select OE_ORDER_LINES_S.nextval from dual'));

        $result = DB::connection('oracle')->table('OE_LINES_IFACE_ALL')->insert([
            'ORDER_SOURCE_ID' => 1001, //need to update when BEA PROD
            'ORIG_SYS_DOCUMENT_REF' => $orig_sys_document_ref,
            'ORIG_SYS_LINE_REF' => $lines_nextval[0]->nextval,
            'LINE_NUMBER' => $line_number,
            'INVENTORY_ITEM_ID' => $item_id,
            'ORDERED_QUANTITY' => $quantity,
            'SHIP_FROM_ORG_ID' => $ship_from_org,
            'ORG_ID' => 81,
            'PRICING_QUANTITY' => $quantity, // need to check
            'UNIT_SELLING_PRICE' => $unit_selling_price,
            'UNIT_LIST_PRICE' => $unit_list_price,
            'PRICE_LIST_ID' => $price_list,
            'PAYMENT_TERM_ID' => 4, // need to check
            'SCHEDULE_SHIP_DATE' => $this->sysdate[0]->sysdate,
            'REQUEST_DATE' => $this->sysdate[0]->sysdate,
            'CREATED_BY' => 0,
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'LINE_TYPE_ID' => 1003,
            'CALCULATE_PRICE_FLAG' => 'N',
            'RETURN_REASON_CODE' => $return_reason,
            'SUBINVENTORY' => $subinventory
        ]);
    }
    //endonline

    public function sorRMAHeaders($price_list_id, $sold_to_org, $ship_to_org, $invoice_to_org, $dr_number)
    {
        $data = array();
        $this->sor_header_next_val = DB::connection('oracle')->select(DB::raw('select OE_ORDER_HEADERS_S.nextval from dual'));
        $data['rma_header'] = $this->sor_header_next_val[0]->nextval;

        $result = DB::connection('oracle')->table('OE_HEADERS_IFACE_ALL')->insert([
            'ORDER_SOURCE_ID' => 1001, //need to update when BEA PROD
            'ORIG_SYS_DOCUMENT_REF' => $this->sor_header_next_val[0]->nextval,
            'ORG_ID' => 81,
            'SOLD_FROM_ORG_ID' => 81,
            'SHIP_FROM_ORG_ID' => 225,
            'ORDERED_DATE' => $this->sysdate[0]->sysdate,
            'ORDER_TYPE_ID' => 1110, //rma
            'SOLD_TO_ORG_ID' => $sold_to_org,
            'PAYMENT_TERM_ID' => 4, //need to check
            'OPERATION_CODE' => 'CREATE',
            'CREATED_BY' => 0,
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'CUSTOMER_PO_NUMBER' => $dr_number,
            'PRICE_LIST_ID' => $price_list_id,
            'CONTEXT' => 'MIDDLEWARE',
            'ATTRIBUTE19' => '',
            'SHIP_TO_ORG_ID' => $ship_to_org,
            'INVOICE_TO_ORG_ID' => $invoice_to_org,
            'BOOKED_FLAG' => 'Y'
        ]);
        return $data;
    }

    public function sorRMALines($line_number, $orig_sys_document_ref, $item_id, $quantity, $price_list, $unit_selling_price, $unit_list_price, $return_reason, $subinventory)
    {
        $lines_nextval = DB::connection('oracle')->select(DB::raw('select OE_ORDER_LINES_S.nextval from dual'));

        $result = DB::connection('oracle')->table('OE_LINES_IFACE_ALL')->insert([
            'ORDER_SOURCE_ID' => 1001, //need to update when BEA PROD
            'ORIG_SYS_DOCUMENT_REF' =>  $orig_sys_document_ref,
            'ORIG_SYS_LINE_REF' => $lines_nextval[0]->nextval,
            'LINE_NUMBER' => $line_number,
            'INVENTORY_ITEM_ID' => $item_id,
            'ORDERED_QUANTITY' => $quantity,
            'SHIP_FROM_ORG_ID' => 225,
            'ORG_ID' => 81,
            'PRICING_QUANTITY' => $quantity, // need to check
            'UNIT_SELLING_PRICE' => $unit_selling_price,
            'UNIT_LIST_PRICE' => $unit_list_price,
            'PRICE_LIST_ID' => $price_list,
            'PAYMENT_TERM_ID' => 4, // need to check
            'SCHEDULE_SHIP_DATE' => $this->sysdate[0]->sysdate,
            'REQUEST_DATE' => $this->sysdate[0]->sysdate,
            'CREATED_BY' => 0,
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'LINE_TYPE_ID' => 1003,
            'CALCULATE_PRICE_FLAG' => 'N',
            'RETURN_REASON_CODE' => $return_reason,
            'SUBINVENTORY' => $subinventory
        ]);
    }

    public function createMOR($item_id, $from_subinventory, $quantity, $transfer_subinventory, $dr_number, $transfer_organization, $reason_id, $organization_id)
    {
        
        $result = DB::connection('oracle')->table('MTL_TRANSACTIONS_INTERFACE')->insert([
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'CREATED_BY' => 0,
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'SOURCE_CODE' => 'MIDDLEWARE',
            'SOURCE_LINE_ID' => 1,
            'SOURCE_HEADER_ID' => 1,
            'PROCESS_FLAG' => 1,
            'INVENTORY_ITEM_ID' => $item_id,
            'ORGANIZATION_ID' => $organization_id,
            'SUBINVENTORY_CODE' => $from_subinventory,
            'TRANSACTION_TYPE_ID' => 21,
            'TRANSACTION_QUANTITY' => $quantity,
            'TRANSACTION_UOM' => 'Pc',
            'TRANSACTION_DATE' => $this->sysdate[0]->sysdate,
            'TRANSFER_ORGANIZATION' => $transfer_organization, //224
            'TRANSFER_SUBINVENTORY' => $transfer_subinventory,
            'TRANSACTION_MODE' => 3,
            'REVISION' => '',
            'SHIPMENT_NUMBER' => $dr_number,
            'REASON_ID' => $reason_id //100-outright SR-02; 
        ]);
    }

    public function sorReceiveHeaders($customer_id)
    {
        $data = array();
        $this->header_next_val = DB::connection('oracle')->select(DB::raw('select RCV_HEADERS_INTERFACE_S.nextval from dual'));
        $this->groups_next_val = DB::connection('oracle')->select(DB::raw('select RCV_INTERFACE_GROUPS_S.NEXTVAL from dual'));
        $this->groups_curr_val = $this->groups_next_val;

        $data['sor_rcv_header'] = $this->header_next_val[0]->nextval;
        $data['sor_rcv_group_nextval'] = $this->groups_next_val[0]->nextval;
        $data['sor_rcv_group_curval'] = $this->groups_curr_val;
        
        $result = DB::connection('oracle')->table('RCV_HEADERS_INTERFACE')->insert([
            'HEADER_INTERFACE_ID' => $this->header_next_val[0]->nextval,
            'GROUP_ID' => $this->groups_next_val[0]->nextval,
            'PROCESSING_STATUS_CODE' => 'PENDING',
            'RECEIPT_SOURCE_CODE' => 'CUSTOMER',
            'TRANSACTION_TYPE' => 'NEW',
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'LAST_UPDATE_LOGIN' => 0,
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'CREATED_BY' => 0,
            'CUSTOMER_ID' => $customer_id,
            'EXPECTED_RECEIPT_DATE' => $this->sysdate[0]->sysdate,
            'VALIDATION_FLAG' => 'Y'
        ]);
        return $data;

    }

    public function sorReceiveLines($dr_number, $branch, $item_id, $quantity, $oe_order_header_id, $oe_order_line_id, $customer_id, $customer_site_id, $header_interface_id, $group_id, $to_organization_id)
    {
        
        $result = DB::connection('oracle')->table('RCV_TRANSACTIONS_INTERFACE')->insert([
            'INTERFACE_TRANSACTION_ID' => $this->transaction_next_val[0]->nextval,
            'HEADER_INTERFACE_ID' => $header_interface_id,
            'GROUP_ID' => $group_id,
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'CREATED_BY' => 0,
            'LAST_UPDATE_LOGIN' => 0,
            'TRANSACTION_TYPE' => 'RECEIVE',
            'TRANSACTION_DATE' => $this->sysdate[0]->sysdate,
            'PROCESSING_STATUS_CODE' => 'PENDING',
            'PROCESSING_MODE_CODE' => 'BATCH',
            'TRANSACTION_STATUS_CODE' => 'PENDING',
            'QUANTITY' => $quantity,
            'UNIT_OF_MEASURE' => 'PIECE',
            'INTERFACE_SOURCE_CODE' => 'RCV',
            'ITEM_ID' => $item_id,
            'AUTO_TRANSACT_CODE' => 'DELIVER',
            'RECEIPT_SOURCE_CODE' => 'CUSTOMER',
            'TO_ORGANIZATION_ID' => $to_organization_id, //224,
            'SOURCE_DOCUMENT_CODE' => 'RMA',
            'DESTINATION_TYPE_CODE' => 'INVENTORY',
            'SUBINVENTORY' => $branch,
            'SHIPMENT_NUM' => $dr_number,
            'EXPECTED_RECEIPT_DATE' => $this->sysdate[0]->sysdate,
            'OE_ORDER_HEADER_ID' => $oe_order_header_id,
            'OE_ORDER_LINE_ID'  => $oe_order_line_id,
            'CUSTOMER_ID'  => $customer_id,
            'CUSTOMER_SITE_ID'  => $customer_site_id,
            'VALIDATION_FLAG' => 'Y'
            
        ]);

    }

    public function sorRMAReceiveLines($dr_number, $branch, $item_id, $quantity, $oe_order_header_id, $oe_order_line_id, $customer_id, $customer_site_id, $header_interface_id, $group_id)
    {
        
        $result = DB::connection('oracle')->table('RCV_TRANSACTIONS_INTERFACE')->insert([
            'INTERFACE_TRANSACTION_ID' => $this->transaction_next_val[0]->nextval,
            'HEADER_INTERFACE_ID' => $header_interface_id,
            'GROUP_ID' => $group_id,
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'CREATED_BY' => 0,
            'LAST_UPDATE_LOGIN' => 0,
            'TRANSACTION_TYPE' => 'RECEIVE',
            'TRANSACTION_DATE' => $this->sysdate[0]->sysdate,
            'PROCESSING_STATUS_CODE' => 'PENDING',
            'PROCESSING_MODE_CODE' => 'BATCH',
            'TRANSACTION_STATUS_CODE' => 'PENDING',
            'QUANTITY' => $quantity,
            'UNIT_OF_MEASURE' => 'PIECE',
            'INTERFACE_SOURCE_CODE' => 'RCV',
            'ITEM_ID' => $item_id,
            'AUTO_TRANSACT_CODE' => 'DELIVER',
            'RECEIPT_SOURCE_CODE' => 'CUSTOMER',
            'TO_ORGANIZATION_ID' => 225,
            'SOURCE_DOCUMENT_CODE' => 'RMA',
            'DESTINATION_TYPE_CODE' => 'INVENTORY',
            'SUBINVENTORY' => $branch,
            'SHIPMENT_NUM' => $dr_number,
            'EXPECTED_RECEIPT_DATE' => $this->sysdate[0]->sysdate,
            'OE_ORDER_HEADER_ID' => $oe_order_header_id,
            'OE_ORDER_LINE_ID'  => $oe_order_line_id,
            'CUSTOMER_ID'  => $customer_id,
            'CUSTOMER_SITE_ID'  => $customer_site_id,
            'VALIDATION_FLAG' => 'Y'
            
        ]);

    }

    public function dtoReceiving($dr_number, $org_id)
    {
        
        $this->header_next_val = DB::connection('oracle')->select(DB::raw('select RCV_HEADERS_INTERFACE_S.nextval from dual'));
        $this->groups_next_val = DB::connection('oracle')->select(DB::raw('select RCV_INTERFACE_GROUPS_S.NEXTVAL from dual'));
        $this->groups_curr_val = $this->groups_next_val;
        
        self::morReceiveHeaders($dr_number, $org_id);
        $shipmentHeader = self::getShipmentHeaders($dr_number);
        $shipmentLines = self::getShipmentLines($shipmentHeader);

        foreach ($shipmentLines as $key => $value) {

            $this->sysdate = DB::connection('oracle')->select(DB::raw('select SYSDATE from dual'));
            $this->transaction_next_val = DB::connection('oracle')->select(DB::raw('select RCV_TRANSACTIONS_INTERFACE_S.NEXTVAL from dual'));
            $this->rcv_next_val = $this->header_next_val;
            
            self::morReceiveLines($dr_number, 
                $value->item_id, 
                $value->quantity_shipped, 
                $value->to_subinventory, 
                $org_id,
                $shipmentHeader, 
                $value->shipment_line_id);
        }

        //CRUDBooster::redirect(CRUDBooster::mainpath(),'DR# '.$dr_number.' has been received successfully!','success')->send();
    }

    public function morReceiveHeaders($dr_number, $org_id)
    {
        $result = DB::connection('oracle')->table('RCV_HEADERS_INTERFACE')->insert([
            'HEADER_INTERFACE_ID' => $this->header_next_val[0]->nextval,
            'GROUP_ID' => $this->groups_next_val[0]->nextval,
            'PROCESSING_STATUS_CODE' => 'PENDING',
            'RECEIPT_SOURCE_CODE' => 'INVENTORY',
            'TRANSACTION_TYPE' => 'NEW',
            'AUTO_TRANSACT_CODE' => 'DELIVER',
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'LAST_UPDATE_LOGIN' => 0,
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'CREATED_BY' => 0,
            'SHIPMENT_NUM' => $dr_number,
            'SHIP_TO_ORGANIZATION_ID' => $org_id,
            'EXPECTED_RECEIPT_DATE' => $this->sysdate[0]->sysdate,
            'VALIDATION_FLAG' => 'Y'
        ]);

    }

    public function morReceiveLines($dr_number, $item_id, $quantity, $branch, $org_id, $shipment_header_id, $shipment_line_id)
    {
        $result = DB::connection('oracle')->table('RCV_TRANSACTIONS_INTERFACE')->insert([
            'INTERFACE_TRANSACTION_ID' => $this->transaction_next_val[0]->nextval,
            'GROUP_ID' => $this->groups_curr_val[0]->nextval,
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'CREATED_BY' => 0,
            'LAST_UPDATE_LOGIN' => 0,
            'TRANSACTION_TYPE' => 'RECEIVE',
            'TRANSACTION_DATE' => $this->sysdate[0]->sysdate,
            'PROCESSING_STATUS_CODE' => 'PENDING',
            'PROCESSING_MODE_CODE' => 'BATCH',
            'TRANSACTION_STATUS_CODE' => 'PENDING',
            'QUANTITY' => $quantity,
            'UNIT_OF_MEASURE' => 'PIECE',
            'INTERFACE_SOURCE_CODE' => 'RCV',
            'ITEM_ID' => $item_id,
            'EMPLOYEE_ID' => 0,
            'AUTO_TRANSACT_CODE' => 'DELIVER',
            'SHIPMENT_HEADER_ID' => $shipment_header_id,
            'SHIPMENT_LINE_ID' => $shipment_line_id,
            'RECEIPT_SOURCE_CODE' => 'INVENTORY',
            'TO_ORGANIZATION_ID' => $org_id,
            'SOURCE_DOCUMENT_CODE' => 'INVENTORY',
            'DESTINATION_TYPE_CODE' => 'INVENTORY',
            'SUBINVENTORY' => $branch,
            'SHIPMENT_NUM' => $dr_number,
            'EXPECTED_RECEIPT_DATE' => $this->sysdate[0]->sysdate,
            'HEADER_INTERFACE_ID' => $this->rcv_next_val[0]->nextval,
            'VALIDATION_FLAG' => 'Y'
            
        ]);

    }

    public function createSIT($dr_number, $inventory_item_id, $quantity, $subinventory, $transfer_subinventory, $locator_id)
    {
        $material_transactions_interface = DB::connection('oracle')->select(DB::raw('select MTL_MATERIAL_TRANSACTIONS_S.NEXTVAL from dual'));

        $result = DB::connection('oracle')->table('MTL_TRANSACTIONS_INTERFACE')->insert([
            'TRANSACTION_INTERFACE_ID' => $material_transactions_interface[0]->nextval,
            'TRANSACTION_HEADER_ID' => $material_transactions_interface[0]->nextval,
            'TRANSACTION_DATE' => $this->sysdate[0]->sysdate,
            'TRANSACTION_UOM' => 'Pc',
            'LOCATOR_ID' => $locator_id,
            'SUBINVENTORY_CODE' => $subinventory,
            'INVENTORY_ITEM_ID' => $inventory_item_id,
            'TRANSACTION_QUANTITY' => $quantity,
            'TRANSACTION_COST' => 0,
            'ORGANIZATION_ID' => 263, //DEO
            'SOURCE_CODE' => 'INV',
            'SOURCE_LINE_ID' => 1,
            'SOURCE_HEADER_ID' => 1,
            'PROCESS_FLAG' => 1,
            'TRANSACTION_MODE' => 3,
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'CREATED_BY' => 0,
            'LAST_UPDATE_LOGIN' => 0,
            'TRANSACTION_SOURCE_TYPE_ID' => 13,
            'TRANSACTION_ACTION_ID' => 2,
            'TRANSACTION_TYPE_ID' => 2,
            'TRANSFER_SUBINVENTORY' => $transfer_subinventory,
            'TRANSFER_ORGANIZATION' => 263, //DEO
            'LOCK_FLAG' => 2,
            'FLOW_SCHEDULE' => 'Y',
            'SCHEDULED_FLAG' => 2,
            'TRANSACTION_REFERENCE' => $dr_number,
            'TRANSACTION_SOURCE_NAME' => $dr_number
        ]); 

    }

    public function updateBEAItemSerial($item_code, $serial)
    {
        $result = DB::connection('oracle')->table('MTL_ITEM_SYSTEMS')->update([
            'SEGMENT20' => $serial
        ])->where('SEGMENT1', $item_code)
        ->where(function ($query)
        {
            $query->where('ORGANIZATION_ID', 223)
                ->orWhere('ORGANIZATION_ID', 224)
                ->orWhere('ORGANIZATION_ID', 225)
                ->orWhere('ORGANIZATION_ID', 263)
                ->orWhere('ORGANIZATION_ID', 243);
        });
    }

    public function createBEAItem($data = [])
    {
        OracleItemInterface::insert([
            'PROCESS_FLAG' => 1,
            'SET_PROCESS_ID' => $data['process_id'], //auto generate
            'TRANSACTION_TYPE' => "CREATE",
            'ORGANIZATION_ID' => $data['organization_id'],
            'SEGMENT1' => $data['digits_code'],
            'ATTRIBUTE3' => $data['upc_code'],
            'ATTRIBUTE6' => $data['supplier_item_code'],
            'DESCRIPTION' => $data['item_description'],
            'ATTRIBUTE7' => $data['brand'],
            'ATTRIBUTE8' => $data['warehouse_category'],
            'ATTRIBUTE1' => $data['current_srp'],
            'ATTRIBUTE9' => $data['purchase_price'],
            'SEGMENT20' => $data['has_serial'],
            'TEMPLATE_ID' => $data['template_id'],
            'LAST_UPDATE_DATE' => $this->sysdate[0]->sysdate,
            'LAST_UPDATED_BY' => 0,
            'CREATION_DATE' => $this->sysdate[0]->sysdate,
            'CREATED_BY' => 0,
            'LAST_UPDATE_LOGIN' => 0
        ]);
        
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

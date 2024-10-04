<?php namespace App\Http\Controllers;

	use App\ApprovalMatrix;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Facades\DB;

	class AdminPulloutTransactionsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "st_document_number";
			$this->limit = "20";
			$this->orderby = "status,asc,created_date,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "pullout";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Created Date","name"=>"created_date"];
			$this->col[] = ["label"=>"ST/REF #","name"=>"st_document_number"];
			$this->col[] = ["label"=>"Received ST #","name"=>"received_st_number"];
			$this->col[] = ["label"=>"MOR/SOR #","name"=>"sor_number"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Digits Code","name"=>"item_code"];
			$this->col[] = ['label'=>'Item Description','name'=>'item_description'];
			$this->col[] = ["label"=>"From WH","name"=>"stores_id","join"=>"stores,pos_warehouse_name"];
			$this->col[] = ["label"=>"To WH","name"=>"wh_to"];
			$this->col[] = ["label"=>"Transport By","name"=>"transport_types_id","join"=>"transport_types,transport_type"];
			$this->col[] = ["label"=>"Scheduled Date","name"=>"schedule_date"];
			$this->col[] = ['label'=>'Serial','name'=>'serial'];

	        $this->button_selected = array();
            if(CRUDBooster::isSuperadmin()){
                $this->button_selected[] = ['label'=>'Set Closed Status', 'icon'=>'fa fa-times-circle', 'name'=>'set_closed_status'];
                $this->button_selected[] = ['label'=>'Set Received Status', 'icon'=>'fa fa-check-circle', 'name'=>'set_received_status'];
                $this->button_selected[] = ['label'=>'Copy Ref to SOR/MOR', 'icon'=>'fa fa-files-o', 'name'=>'copy_ref_to_sor_mor'];
                $this->button_selected[] = ['label'=>'Re-run ST Creation', 'icon'=>'fa fa-refresh', 'name'=>'rerun_st_creation'];
                $this->button_selected[] = ['label'=>'Re-run ST Receiving', 'icon'=>'fa fa-refresh', 'name'=>'rerun_st_receiving'];
                $this->button_selected[] = ['label'=>'Re-run Stock Out', 'icon'=>'fa fa-refresh', 'name'=>'rerun_stock_out'];
                $this->button_selected[] = ['label'=>'Push MOR', 'icon'=>'fa fa-files-o', 'name'=>'push_mor'];
            }
	    }
		
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	        if($button_name == 'set_closed_status'){
				DB::table('pullout')->whereIn('id', $id_selected)->update([
					'status' => 'CLOSED'
				]);
			} 
			if($button_name == 'set_received_status'){
				DB::table('pullout')->whereIn('id', $id_selected)->update([
					'status' => 'RECEIVED'
				]);
			} 
			if($button_name == 'copy_ref_to_sor_mor'){
			    DB::table('pullout')->whereIn('id', $id_selected)->update([
					'sor_number' => DB::raw("`st_document_number`")
				]);
			}
			if($button_name == 'rerun_st_receiving'){
			    $posItemDetails = array();
			    $postedST = array();
				$pulloutItems = DB::table('pullout')->whereIn('id', $id_selected)->get();
				$store = DB::table('stores')->where('pos_warehouse',$pulloutItems[0]->wh_from)->first();
				
    			foreach ($pulloutItems as $key_item => $value_item) {
    			    
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
    			
                $receivedDate = $pulloutItems[0]->received_st_date;
                
    			if($pulloutItems[0]->channel_id != 4){
                    if($pulloutItems[0]->transaction_type == 'STW'){
                        $postedST = app(POSPushController::class)->posCreateStockTransferReceiving($pulloutItems[0]->st_document_number, $store->pos_warehouse_transit_branch, $store->pos_warehouse_transit, $pulloutItems[0]->wh_to, 'STW-'.$store->pos_warehouse_name,  date("Ymd", strtotime($receivedDate)), $posItemDetails);
                    }
                    else{
                        $postedST = app(POSPushController::class)->posCreateStockTransferReceiving($pulloutItems[0]->st_document_number, $store->pos_warehouse_rma_branch, $store->pos_warehouse_rma, $pulloutItems[0]->wh_to, 'STR-'.$store->pos_warehouse_name,  date("Ymd", strtotime($receivedDate)), $posItemDetails);
                    }
                    \Log::info('received ST:'.json_encode($postedST));
                    $received_st_number = $postedST['data']['record']['fdocument_no'];
                    if($postedST['data']['record']['fresult'] != "ERROR" && !empty($received_st_number)){
                        if($pulloutItems[0]->transaction_type == 'STW'){
                            app(POSPushController::class)->posCreateStockAdjustmentOutReceiving($received_st_number,'DIGITSWAREHOUSE', date("Ymd", strtotime($receivedDate)), $posItemDetails);
                        }
                        
                    }
                }
                
                $st_number = $postedST['data']['record']['fdocument_no'];
                if($postedST['data']['record']['fresult'] == "ERROR"){
					$error = $postedST['data']['record']['errors']['error'];
					CRUDBooster::redirect(CRUDBooster::mainpath(),'Fail! '.$error,'warning')->send();
				}
                DB::table('pullout')->whereIn('id',$id_selected)->update([
                    'received_st_number' => $st_number
                ]);
			} 
			
			if($button_name == 'rerun_st_creation'){
			    $posItemDetails = array();
			    $postedST = array();
				$pulloutItems = DB::table('pullout')->whereIn('id', $id_selected)->get();
				$store = DB::table('stores')->where('pos_warehouse',$pulloutItems[0]->wh_from)->first();
				
    			foreach ($pulloutItems as $key_item => $value_item) {
    			    
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
    			
                $createdDate = $pulloutItems[0]->created_date;
                $refcode = 'BEAPOSMW'.date('His');
    // 			if($pulloutItems[0]->channel_id != 4 && $store->){
                    if($pulloutItems[0]->transaction_type == 'STW'){
                        $postedST = app(POSPushController::class)->posRerunCreateStockTransfer($refcode, $store->pos_warehouse_branch, $store->pos_warehouse, $store->pos_warehouse_transit, 'STW-'.$store->pos_warehouse_name,  date("Ymd", strtotime($createdDate)), $posItemDetails);
                    }
                    else{
                        $postedST = app(POSPushController::class)->posRerunCreateStockTransfer($refcode, $store->pos_warehouse_branch, $store->pos_warehouse, $store->pos_warehouse_rma, 'STR-'.$store->pos_warehouse_name,  date("Ymd", strtotime($createdDate)), $posItemDetails);
                    }
                    \Log::info('rerun create ST:'.json_encode($postedST));
                // }
                
                $st_number = $postedST['data']['record']['fdocument_no'];
                if($postedST['data']['record']['fresult'] == "ERROR"){
					$error = $postedST['data']['record']['errors']['error'];
					CRUDBooster::redirect(CRUDBooster::mainpath(),'Fail! '.$error,'warning')->send();
				}
                DB::table('pullout')->whereIn('id',$id_selected)->update([
                    'st_document_number' => $st_number,
                    // 'status' => 'FOR PROCESSING',
                    // 'sor_number' => NULL,
                    // 'received_st_number' => NULL,
                    // 'received_st_date' => NULL
                ]);
                
                
        //         foreach($pulloutItems as $key_item => $value_item){
        //             $bea_item_id = DB::table('items')->where('digits_code',$value_item->item_code)->value('bea_item_id');
        //             if($pulloutItems[0]->transaction_type == 'RMA'){
        // 			    app(EBSPushController::class)->createMOR($bea_item_id, $store->doo_subinventory, ($value_item->quantity)*(-1), 'TO CHECK', $st_number, 225, $value_item->reason_id, 223);
        			    
        // 			}
        // 			elseif($pulloutItems[0]->transaction_type == 'STW'){
        // 			    app(EBSPushController::class)->createMOR($bea_item_id, $store->doo_subinventory, ($value_item->quantity)*(-1), $store->org_subinventory, $st_number, 224, $value_item->reason_id, 223);
        			    
        // 			}
        //         }
                
			} 
			
			if($button_name == 'rerun_stock_out'){
			    $posItemDetails = array();
			    $pulloutItems = DB::table('pullout')->whereIn('id', $id_selected)->get();
			    foreach ($pulloutItems as $key_item => $value_item) {
    			    
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
    			
                $receivedDate = $pulloutItems[0]->received_st_date;
                
			    if($pulloutItems[0]->transaction_type == 'STW'){
                    $stockOut = app(POSPushController::class)->posCreateStockAdjustmentOutReceiving($pulloutItems[0]->received_st_number,'DIGITSWAREHOUSE', date("Ymd", strtotime($receivedDate)), $posItemDetails);
                
			        \Log::info('rerun stock out:'.json_encode($stockOut));
			        if($stockOut['data']['record']['fresult'] == "ERROR"){
    					$error = $stockOut['data']['record']['errors']['error'];
    					CRUDBooster::redirect(CRUDBooster::mainpath(),'Fail! '.$error,'warning')->send();
    				}
			    }
			}
			elseif($button_name == 'push_mor'){
				$pulloutDetails = DB::table('pullout')->whereIn('id', $id_selected)->get();
				$store = DB::table('stores')->where('pos_warehouse',$pulloutDetails[0]->wh_from)->first();
			    foreach($pulloutDetails as $key => $value){
					$item = DB::table('items')->where('digits_code',$value->item_code)->first();
					$reason = DB::table('reason')->where('id',$value->reason_id)->first();
					app(EBSPushController::class)->createMOR(
						$item->bea_item_id, 
						$store->doo_subinventory, 
						($value->quantity)*(-1), 
						$store->org_subinventory, 
						$value->st_document_number, 
						($store->org_subinventory == 'ECOM') ? 263 : 224, 
						$reason->bea_mo_reason, 
						223);
				}
			}
	    }
	    public function hook_query_index(&$query) {
	        //Your code here
	        if(!CRUDBooster::isSuperadmin()){
				$store = DB::table('stores')->where('id', CRUDBooster::myStore())->first();
				if (CRUDBooster::myPrivilegeName() == "Logistics") {
					$query->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status')
					->where('transport_types_id',1)
					->distinct();
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(),["Approver","Franchise Approver"])){
					//get approval matrix
					$approvalMatrix = ApprovalMatrix::where('approval_matrix.cms_users_id', CRUDBooster::myId())->get();
					
					$approval_array = array();
					foreach($approvalMatrix as $matrix){
						array_push($approval_array, $matrix->store_list);
					}
					$approval_string = implode(",",$approval_array);
					$storeList = array_map('intval',explode(",",$approval_string));
	
					$query->whereIn('pullout.stores_id', array_values((array)$storeList))
						->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status','pullout.created_date')->distinct();
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Warehouse","Warehouse Online","RMA"])){
					$query->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status','pullout.created_date')
					->where('wh_to',$store->pos_warehouse)->distinct();
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Online Ops","Retail Ops","Franchise Ops","Online Viewer"])) {
					$query->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status','pullout.created_date')
					->where('pullout.channel_id',CRUDBooster::myChannel())->distinct();
				}
				elseif(CRUDBooster::myPrivilegeName() == "Rtl Fra Ops"){
					$query->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status','pullout.created_date')
					->whereIn('pullout.channel_id',[1,2])->distinct();
				}
				elseif(CRUDBooster::myPrivilegeName() == "Rtl Onl Viewer"){
					$query->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status','pullout.created_date')
					->whereIn('pullout.channel_id',[1,4])->distinct();
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Audit","Inventory Control","Merch"])){
				    $query->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status','pullout.created_date')->distinct();
				}
				else{
					$query->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status','pullout.created_date')
					->whereIn('pullout.stores_id',CRUDBooster::myStore())->distinct();
				}
			}
			    
	    }
		   
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    	if($column_index == 5){
				if($column_value == "PENDING"){
					$column_value = '<span class="label label-warning">PENDING</span>';
				}
				else if($column_value == "FOR PICKLIST"){
					$column_value = '<span class="label label-warning">FOR PICKLIST</span>';
				}
				else if($column_value == "FOR PICK CONFIRM"){
					$column_value = '<span class="label label-warning">FOR PICK CONFIRM</span>';
				}
				else if($column_value == "FOR SCHEDULE"){
					$column_value = '<span class="label label-warning">FOR SCHEDULE</span>';
				}
				else if($column_value == "FOR RECEIVING"){
					$column_value = '<span class="label label-warning">FOR RECEIVING</span>';
				}
				else if($column_value == "FOR PROCESSING"){
					$column_value = '<span class="label label-info">FOR PROCESSING</span>';
				}
				else if($column_value == "RECEIVED"){
					$column_value = '<span class="label label-success">RECEIVED</span>';
				}
				else if($column_value == "PARTIALLY RECEIVED"){
					$column_value = '<span class="label label-success">PARTIALLY RECEIVED</span>';
				}
				else if($column_value == "VOID"){
					$column_value = '<span class="label label-danger">VOID</span>';
				}
				else if($column_value == "CLOSED"){
					$column_value = '<span class="label label-danger">CLOSED</span>';
				}
			}
			elseif($column_index == 10){
				if($column_value == "Logistics"){
					$column_value = '<span class="label label-default">LOGISTICS</span>';
				}
				elseif($column_value == "Hand Carry"){
					$column_value = '<span class="label label-primary">HAND CARRY</span>';
				}
			}
	    }

	}
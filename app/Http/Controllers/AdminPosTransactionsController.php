<?php 

	namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminPosTransactionsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "st_document_number";
			$this->limit = "20";
			$this->orderby = "received_st_date,desc";
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
			$this->button_export = true;
			$this->table = "pos_pull";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Created Date","name"=>"pos_pull_headers.created_date"];
			$this->col[] = ["label"=>"ST#","name"=>"pos_pull_headers.st_document_number"];
			$this->col[] = ["label"=>"Received ST#","name"=>"pos_pull_headers.received_st_number"];
			$this->col[] = ["label"=>"Received Date","name"=>"pos_pull_headers.received_st_date"];
			$this->col[] = ['label'=>'Status','name'=>'pos_pull_headers.status'];
			$this->col[] = ["label"=>"WH From","name"=>"pos_pull_headers.wh_from"];
			$this->col[] = ["label"=>"WH To","name"=>"pos_pull_headers.wh_to"];
			$this->col[] = ["label"=>"Digits Code","name"=>"pos_pull.item_code"];
			$this->col[] = ['label'=>'Item Description','name'=>'pos_pull.item_description'];
			$this->col[] = ['label'=>'Qty','name'=>'pos_pull.quantity'];
			$this->col[] = ["label"=>"Memo","name"=>"pos_pull_headers.memo"];
			$this->col[] = ['label'=>'Serial','name'=>'pos_pull.serial'];
			
			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			# END FORM DO NOT REMOVE THIS LINE

			
	        $this->button_selected = array();
            if(CRUDBooster::isSuperadmin()){
            	$this->button_selected[] = ['label'=>'Set Closed Status', 'icon'=>'fa fa-times-circle', 'name'=>'set_closed_status'];
            	$this->button_selected[] = ['label'=>'Set Received Status', 'icon'=>'fa fa-check-circle', 'name'=>'set_received_status'];
            	$this->button_selected[] = ['label'=>'Re-run ST Creation', 'icon'=>'fa fa-refresh', 'name'=>'rerun_st_creation'];
            	$this->button_selected[] = ['label'=>'Re-run ST Receiving', 'icon'=>'fa fa-refresh', 'name'=>'rerun_st_receiving'];
            	$this->button_selected[] = ['label'=>'Reset Serial Flag', 'icon'=>'fa fa-refresh', 'name'=>'reset_serial_flag'];
            }
	    }

		
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	        if($button_name == 'set_closed_status'){
				DB::table('pos_pull')->whereIn('id', $id_selected)->update([
					'status' => 'CLOSED'
				]);
			} 
			if($button_name == 'set_received_status'){
				DB::table('pos_pull')->whereIn('id', $id_selected)->update([
					'status' => 'RECEIVED'
				]);
			}
			if($button_name == 'rerun_st_creation'){
	            $posItemDetails = array();
				$stsDetails = DB::table('pos_pull')->whereIn('id', $id_selected)->get();
				foreach($stsDetails as $value){
				    $itemDetails = DB::table('items')->where('digits_code',$value->item_code)->first();
				    if($value->has_serial == 1){
				        $serials = explode(",",$value->serial);
				        foreach($serials as $serial){
				            $posItemDetails[$key.'-'.$serial] = [
    							'item_code' => $value->item_code,
    							'quantity' => 1,
    							'serial_number' => $serial,
    							'item_price' => $itemDetails->store_cost
    						];
				        }
				    }
				    else{
				        $posItemDetails[$value->item_code.'-'.$key] = [
    						'item_code' => $value->item_code,
    						'quantity' => $value->quantity,
    						'item_price' => $itemDetails->store_cost
    					];
				    }
				}

                $refcode = 'BEAPOSMW-STS-'.date('His');
                $whfrom = DB::table('stores')->where('pos_warehouse', $stsDetails[0]->wh_from)->first();
                $whto = DB::table('stores')->where('pos_warehouse', $stsDetails[0]->wh_to)->first();
                $reason = DB::table('reason')->where('id', $stsDetails[0]->reason_id)
                    ->where('transaction_type_id',4)->first();

                $transfer_destination = $whfrom->pos_warehouse_transit;

                if(str_contains($whto->bea_so_store_name, 'SERVICE CENTER') && !str_contains($reason->pullout_reason, 'REQUEST')){
                    $transfer_destination = $whfrom->pos_warehouse_rma;
                }
                
                $postedST = app(POSPushController::class)->posCreateStockTransfer($refcode, 
                    $whfrom->pos_warehouse_branch, 
                    $whfrom->pos_warehouse, 
                    $transfer_destination, 
                    $stsDetails[0]->memo, 
                    $posItemDetails);
                
                \Log::info('sts create ST: '.json_encode($postedST));
                //20210215 add checking if st number is not null
                $st_number = $postedST['data']['record']['fdocument_no'];
                DB::table('pos_pull')->whereIn('id',$id_selected)->update([
                    'st_document_number' => $st_number
                ]);

			} 

			if($button_name == 'reset_serial_flag'){
				\Log::info('---reset_serial_flag---');
				try {
					DB::table('serials')->whereIn('pos_pull_id', $id_selected)->update([
						'count' => 0,
						'status' => 'PENDING'
					]);
				} catch (\Exception $e) {
					\Log::error('Error!'.$e->getMessage());
				}
				\Log::info('---done resetting serial_flag---');
			}

			if($button_name == 'rerun_st_receiving'){
			    \Log::info('---rerun_st_receiving---');
			    $posItemDetails = array();
				$stsDetails = DB::table('pos_pull')->whereIn('id', $id_selected)->get();
				
				foreach($stsDetails as $key => $value){
				    $itemDetails = DB::table('items')->where('digits_code',$value->item_code)->first();
				    if($value->has_serial == 1){
				        $serials = explode(",",$value->serial);
				        foreach($serials as $serial){
				            $posItemDetails[$key.'-'.$serial] = [
    							'item_code' => $value->item_code,
    							'quantity' => 1,
    							'serial_number' => $serial,
    							'item_price' => $itemDetails->store_cost
    						];
				        }
				    }
				    else{
				        $posItemDetails[$value->item_code.'-'.$key] = [
    						'item_code' => $value->item_code,
    						'quantity' => $value->quantity,
    						'item_price' => $itemDetails->store_cost
    					];
				    }
				}

                $refcode = $stsDetails[0]->st_document_number;
                $receivedDate = $stsDetails[0]->received_st_date;
                $whfrom = DB::table('stores')->where('pos_warehouse', $stsDetails[0]->wh_from)->first();
                $whto = DB::table('stores')->where('pos_warehouse', $stsDetails[0]->wh_to)->first();
                $reason = DB::table('reason')->where('id', $stsDetails[0]->reason_id)
                    ->where('transaction_type_id',4)->first();

                $transfer_origin = $whfrom->pos_warehouse_transit;
                $transfer_branch = $whfrom->pos_warehouse_transit_branch;
                $transfer_destination = $whto->pos_warehouse;

                if(str_contains($whto->bea_so_store_name, 'SERVICE CENTER') && !str_contains($reason->pullout_reason, 'REQUEST')){
                    $transfer_origin = $whfrom->pos_warehouse_rma;
                    $transfer_branch = $whfrom->pos_warehouse_rma_branch;
                    $transfer_destination = $whto->pos_warehouse_rma;
                }
                \Log::info('---push to pos---');
                //app(POSPushController::class)
                $postedST = (new POSPushController)->posCreateStockTransferReceiving($refcode, 
                    $transfer_branch, 
                    $transfer_origin, 
                    $transfer_destination, 
                    "STS", 
                    date("Ymd", strtotime($receivedDate)),
                    $posItemDetails);
                
                \Log::info('sts create rcv ST: '.json_encode($postedST));
                //20210215 add checking if st number is not null
                $st_number = $postedST['data']['record']['fdocument_no'];
                DB::table('pos_pull')->whereIn('id',$id_selected)->update([
                    'received_st_number' => $st_number
                ]);
			} 
	    }
	    public function hook_query_index(&$query) {
	        $query->addSelect(
				'pos_pull_headers.created_date',
				'pos_pull_headers.st_document_number',
				'pos_pull_headers.received_st_number',
				'pos_pull_headers.received_st_date',
				'pos_pull_headers.status',
				'pos_pull_headers.wh_from',
				'pos_pull_headers.wh_to',
				'pos_pull_headers.memo'
			)->leftJoin('pos_pull_headers','pos_pull.pos_pull_header_id','=','pos_pull_headers.id');
	        // dd($query->toSql());    
	    }  
	    public function hook_row_index($column_index,&$column_value) {	        
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
	    }

	}
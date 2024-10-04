<?php namespace App\Http\Controllers;

	use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

	class AdminBeaTransactionsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "order_number";
			$this->limit = "20";
			$this->orderby = "ship_confirmed_date,desc";
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
			$this->table = "ebs_pull";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Created Date","name"=>"data_pull_date"];
			$this->col[] = ["label"=>"SI #","name"=>"si_document_number"];
			$this->col[] = ["label"=>"ST #","name"=>"st_document_number"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Received Date","name"=>"received_at"];
			$this->col[] = ["label"=>"Order Number","name"=>"order_number"];
			$this->col[] = ["label"=>"Line Number","name"=>"line_number"];
			$this->col[] = ["label"=>"Ordered Item","name"=>"ordered_item"];
			$this->col[] = ["label"=>"Ordered Quantity","name"=>"ordered_quantity"];
			$this->col[] = ["label"=>"Shipped Quantity","name"=>"shipped_quantity"];
			$this->col[] = ["label"=>"Customer Name","name"=>"customer_name"];
			$this->col[] = ["label"=>"Transaction Type","name"=>"transaction_type"];
			$this->col[] = ["label"=>"DR Number","name"=>"dr_number"];
			$this->col[] = ["label"=>"Ship Confirmed Date","name"=>"ship_confirmed_date"];
			$this->col[] = ["label"=>"Serial 1","name"=>"serial1"];
			$this->col[] = ["label"=>"Serial 2","name"=>"serial2"];
			$this->col[] = ["label"=>"Serial 3","name"=>"serial3"];
			$this->col[] = ["label"=>"Serial 4","name"=>"serial4"];
			$this->col[] = ["label"=>"Serial 5","name"=>"serial5"];
			$this->col[] = ["label"=>"Serial 6","name"=>"serial6"];
			$this->col[] = ["label"=>"Serial 7","name"=>"serial7"];
			$this->col[] = ["label"=>"Serial 8","name"=>"serial8"];
			$this->col[] = ["label"=>"Serial 9","name"=>"serial9"];
			$this->col[] = ["label"=>"Serial 10","name"=>"serial10"];
			$this->col[] = ["label"=>"Serial 11","name"=>"serial11"];
			$this->col[] = ["label"=>"Serial 12","name"=>"serial12"];
			$this->col[] = ["label"=>"Serial 13","name"=>"serial13"];
			$this->col[] = ["label"=>"Serial 14","name"=>"serial14"];
			$this->col[] = ["label"=>"Serial 15","name"=>"serial15"];

			$this->form = [];

	        $this->addaction = array();
			if(CRUDBooster::isSuperadmin()) {
				// $this->addaction[] = ['title'=>'Accepted Date','url'=>'accepted_date/[dr_number]','icon'=>'fa fa-archive','color'=>'success','showIf'=>"[transaction_type] == 'SO'"];
				// $this->addaction[] = ['title'=>'Close Trip','url'=>'close_trip/[dr_number]','icon'=>'fa fa-times','color'=>'info','showIf'=>"[transaction_type] == 'SO'"];
				// $this->addaction[] = ['title'=>'Receiving','url'=>'doo_receiving/[dr_number]','icon'=>'fa fa-envelope','color'=>'warning','showIf'=>"[transaction_type] == 'MO'"];
				//$this->addaction[] = ['title'=>'Check Product','url'=>'check_product/[ordered_item]/[dr_number]','icon'=>'fa fa-share','color'=>'primary'];
				//$this->addaction[] = ['title'=>'Post SI','url'=>'post_si/[dr_number]','icon'=>'fa fa-share','color'=>'primary'];
				//$this->addaction[] = ['title'=>'Post ST','url'=>'post_st/[dr_number]','icon'=>'fa fa-exchange','color'=>'warning'];
				//$this->addaction[] = ['title'=>'Check ST','url'=>'check_st/[st_document_number]','icon'=>'fa fa-check','color'=>'info'];
			}
			
	        $this->button_selected = array();
			$this->button_selected[] = ['label'=>'Set Pending Status', 'icon'=>'fa fa-check-circle', 'name'=>'set_pending_status'];
			$this->button_selected[] = ['label'=>'Set Closed Status', 'icon'=>'fa fa-times-circle', 'name'=>'set_closed_status'];
			$this->button_selected[] = ['label'=>'Set Received Status', 'icon'=>'fa fa-check-circle', 'name'=>'set_received_status'];
			$this->button_selected[] = ['label'=>'Push DOT Interface', 'icon'=>'fa fa-arrow-circle-up','name'=>'push_interface'];
			$this->button_selected[] = ['label'=>'Push DOTR Interface', 'icon'=>'fa fa-arrow-circle-up','name'=>'push_rcv_interface'];
			$this->button_selected[] = ['label'=>'Rerun DR Receiving','icon'=>'fa fa-refresh','name'=>'rerun_dr_receiving'];
			$this->button_selected[] = ['label'=>'Push SIT Interface','icon'=>'fa fa-arrow-circle-up','name'=>'push_sit_interface'];
	        $this->button_selected[] = ['label'=>'Reset Serial Flag','icon'=>'fa fa-refresh','name'=>'reset_serial_flag'];
	                
	        $this->index_button = array();
	        if(CRUDBooster::getCurrentMethod() == "getIndex"){
				$this->index_button[] = ['label'=>'Move Order Pull','url'=>'bea_transactions/move_order_pull','icon'=>'fa fa-check','color'=>'info'];
				// $this->index_button[] = ['label'=>'Execute Phase II','url'=>'doAllPhaseII','icon'=>'fa fa-check','color'=>'warning'];
	        }
	        
	    }

	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
			if($button_name == 'set_pending_status'){
				DB::table('ebs_pull')->whereIn('id', $id_selected)->update([
					'status' => 'PENDING'
				]);
			}
			elseif($button_name == 'set_received_status'){
				DB::table('ebs_pull')->whereIn('id', $id_selected)->update([
					'status' => 'RECEIVED'
				]);
			}
			elseif($button_name == 'set_closed_status'){
				DB::table('ebs_pull')->whereIn('id', $id_selected)->update([
					'status' => 'CLOSED'
				]);
			}
			elseif($button_name == 'push_interface'){
			    $drDetails = DB::table('ebs_pull')->whereIn('id', $id_selected)->get();
			    foreach($drDetails as $key => $value){
			        $customer_name = DB::table('stores')->where('bea_mo_store_name', $value->customer_name)->select('doo_subinventory','channel_id')->first();
			        if($customer_name->channel_id == 4){
			            app(EBSPushController::class)->createDOT($value->dr_number, $value->ordered_item_id, $value->shipped_quantity, $customer_name->doo_subinventory, $value->locator_id,263);
			        }
			        else{
			            app(EBSPushController::class)->createDOT($value->dr_number, $value->ordered_item_id, $value->shipped_quantity, $customer_name->doo_subinventory, $value->locator_id);
			        }
			        
			    }
			    
			}
			elseif($button_name == 'push_rcv_interface'){
			    $drDetails = DB::table('ebs_pull')->whereIn('id', $id_selected)->get();
			    foreach($drDetails as $key => $value){
			        app(EBSPushController::class)->pushdooReceivingInterface($value->dr_number, $value->ordered_item_id);
			    }
			}
			elseif($button_name == 'push_sit_interface'){
			    $drDetails = DB::table('ebs_pull')->whereIn('id', $id_selected)->get();
			    foreach($drDetails as $key => $value){
			        $customer_name = DB::table('stores')->where('bea_mo_store_name', $value->customer_name)->select('sit_subinventory')->first();
			        app(EBSPushController::class)->createSIT($value->dr_number, $value->ordered_item_id, $value->shipped_quantity, 'STAGINGMO', $customer_name->sit_subinventory, $value->locator_id);
			    }
			}
			elseif($button_name == 'rerun_dr_receiving'){
			    $drDetails = DB::table('ebs_pull')->whereIn('id', $id_selected)->select('dr_number','customer_name','transaction_type')->distinct()->get();
                $record = false;
                foreach($drDetails as $key => $value) {
                    if($value->transaction_type == 'SO'){
                    	$stores = DB::table('stores')->where('bea_so_store_name', $value->customer_name)->first();
                    }
                    else{
                    	$stores = DB::table('stores')->where('bea_mo_store_name', $value->customer_name)->first();
                    }
                    //edited 20220318 due to soap error
                    $stockAdjustments = app(POSPullController::class)->getStockAdjustment($value->dr_number); //timeout while checking
                    	
                    if($stockAdjustments['data']['record']['fstatus_flag'] != 'POSTED'){
                    	if(!in_array(substr($stores->bea_mo_store_name, -3),['FBV','FBD'])){
                    	    $postedSI = app(POSPushController::class)->postSI($value->dr_number);
                    	}
                    }
                    // check first if ST is existing
                    $stockTransfers = app(POSPullController::class)->getStockTransferByRef($value->dr_number);
                    
                    if($stockTransfers['data']['record']['fstatus_flag'] != 'POSTED'){
                    	if(!in_array(substr($stores->bea_mo_store_name, -3),['FBV','FBD'])){
                    		app(POSPushController::class)->postST($value->dr_number, 'DIGITSWAREHOUSE', $stores->pos_warehouse);
                    	}	
                    	
                    	if($value->transaction_type == 'SO'){
                    		app(EBSPushController::class)->acceptedDate($value->dr_number);
                    		app(EBSPushController::class)->closeTrip($value->dr_number);
                    
                    	}
                    	elseif($value->transaction_type == 'MO' && substr($stores->bea_mo_store_name, -3) != 'FBD'){
                    	    if(empty(app(EBSPullController::class)->getShipmentRcvHeadersInterface($value->dr_number))){
                    		    app(EBSPushController::class)->dooReceiving($value->dr_number);
                    		  //  app(EBSPushController::class)->receivingTransaction();
                    	    }
                    	}
                    	elseif($value->transaction_type == 'MO' && substr($stores->bea_mo_store_name, -3) == 'FBD'){
                    		foreach ($value->digits_code as $key_item => $value_item) {
                    			app(EBSPushController::class)->createSIT($value->dr_number, 
                    				$value->bea_item_id[$key_item], 
                    				$value->st_quantity[$key_item], 
                    				'STAGINGMO',
                    				$stores->sit_subinventory,
                    				$value->locator_id
                    			);
                    		}
                    	}
                        /*
                    	DB::table('ebs_pull')->where('dr_number',$value->dr_number)->update([
                    		'status' => 'RECEIVED',
                    		'received_at' => date('Y-m-d H:i:s')
                    	]);
                    	*/
                    
                    	$record = true;
                    }
                    if($record){
                        CRUDBooster::insertLog(trans("crudbooster.dr_received", ['dr_number' =>$value->dr_number]));
                    	CRUDBooster::redirect(CRUDBooster::mainpath(),'DR# '.$value->dr_number.' has been received!','success')->send();
                    }
                    else{
                    	CRUDBooster::redirect(CRUDBooster::mainpath(),'DR# '.$value->dr_number.' has not been received!','danger')->send();
                    }
                }
			}
			elseif($button_name == 'reset_serial_flag'){
				Log::info('---reset_serial_flag---');
				try {
					DB::table('serials')->whereIn('ebs_pull_id', $id_selected)->update([
						'counter' => 0,
						'status' => 'PENDING'
					]);
				} catch (Exception $e) {
					Log::error('Error!'.$e->getMessage());
				}
				Log::info('---done resetting serial_flag---');
			}
	    }
		  
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    	if($column_index == 4){
				if($column_value == "PENDING"){
					$column_value = '<span class="label label-warning">PENDING</span>';
				}
				else if($column_value == "RECEIVED"){
					$column_value = '<span class="label label-success">RECEIVED</span>';
				}
				else if($column_value == "VOID"){
					$column_value = '<span class="label label-danger">VOID</span>';
				}
				else if($column_value == "CLOSED"){
					$column_value = '<span class="label label-danger">CLOSED</span>';
				}
	    	}
	    }
		
		public function doAllPhaseI() {
			$beaDetails = DB::table('ebs_pull')
				->where('status','PENDING')
				->select('dr_number','customer_name','st_document_number','transaction_type')
				->distinct('dr_number')
				->get();

			//do post si
			$stores = array();

			foreach ($beaDetails as $key => $value) {

				// check first if SI is existing
				$stockAdjustments = app(POSPullController::class)->getStockAdjusment($value->dr_number);
				
				if($stockAdjustments['data']['record']['fstatus_flag'] != 'POSTED' || $stockAdjustments['return_code'] != 0){

					app(POSPushController::class)->postSI($value->dr_number);
				}

				if($value->transaction_type == 'SO'){
					$stores = DB::table('stores')->where('bea_so_store_name', $value->customer_name)->first();
				}
				else{
					$stores = DB::table('stores')->where('bea_mo_store_name', $value->customer_name)->first();
				}

				// check first if ST is existing
				$stockTransfers = app(POSPullController::class)->getStockTransfer($value->st_document_number);

				if($stockTransfers['data']['record']['fstatus_flag'] != 'POSTED' || $stockTransfers['return_code'] != 0){
					app(POSPushController::class)->postST($value->dr_number, $stores->pos_warehouse);

					DB::table('ebs_pull')->where('dr_number', $value->dr_number)->update([
						'status' => 'FOR RECEIVING'
					]);
				}

			}			

			CRUDBooster::redirect(CRUDBooster::adminpath('bea_transactions'),'Phase 1 completed!','success')->send();
		}

		public function doAllPhaseII() {

			$beaDetails = DB::table('ebs_pull')
				->where('status','FOR RECEIVING')
				->select('dr_number','customer_name','st_document_number','transaction_type')
				->distinct('dr_number')
				->get();

			foreach ($beaDetails as $key => $value) {
				
				$posSTDetail = app(POSPullController::class)->getStockTransfer($value->st_document_number);
				
				if($posSTDetail['data']['record']['fstatus_flag'] == 'POSTED'){
					
					if($value->transaction_type == 'SO'){
						app(EBSPushController::class)->acceptedDate($value->dr_number);
						app(EBSPushController::class)->closeTrip($value->dr_number);
					}
					else{
						app(EBSPushController::class)->dooReceiving($value->dr_number);
					}
					
				}
			}

			CRUDBooster::redirect(CRUDBooster::adminpath('bea_transactions'),'Phase 2 completed!','success')->send();
		}

		public function getMoveOrder() {
		    $data = array();
            $data['page_title'] = 'Manual Modules';
            $this->cbView("beach.move_order",$data);
		}

	}
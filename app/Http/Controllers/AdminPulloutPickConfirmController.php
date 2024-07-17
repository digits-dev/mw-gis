<?php 

	namespace App\Http\Controllers;

	use Session;
	use DB;
	use CRUDBooster;
	use Illuminate\Http\Request;
	use App\ApprovalMatrix;
	use App\Pullout;
	use App\StoreName;

	class AdminPulloutPickConfirmController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "st_document_number";
			$this->limit = "20";
			$this->orderby = "created_date,desc";
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
			$this->col[] = ["label"=>"ST/REF #","name"=>"st_document_number"];
			$this->col[] = ["label"=>"Received ST #","name"=>"received_st_number"];
			$this->col[] = ["label"=>"MOR/SOR #","name"=>"sor_number"];
			$this->col[] = ["label"=>"From WH","name"=>"stores_id","join"=>"stores,pos_warehouse_name"];
			$this->col[] = ["label"=>"To WH","name"=>"wh_to"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Transport By","name"=>"transport_types_id","join"=>"transport_types,transport_type"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_date"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			
	        $this->addaction = array();
			if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(), ["Online WSDM"])){
				$this->addaction[] = ['title'=>'Pick Confirm','url'=>CRUDBooster::mainpath('pick-confirm').'/[st_document_number]','icon'=>'fa fa-cube','color'=>'info','showIf'=>"[status]=='FOR PICK CONFIRM'"];
			}
			$this->addaction[] = ['title'=>'Detail','url'=>CRUDBooster::mainpath('details').'/[st_document_number]','icon'=>'fa fa-eye','color'=>'primary'];
	        
	        $this->button_selected = array();
			if(CRUDBooster::myPrivilegeName() == "Online WSDM"){
				$this->button_selected[] = ['label'=>'Pick Confirm', 'icon'=>'fa fa-cube', 'name'=>'pick_confirm'];
			}
	        
	    }
		
	    public function hook_query_index(&$query) {
	        //Your code here
	        if(!CRUDBooster::isSuperadmin()){
				$store = DB::table('stores')->whereIn('id', CRUDBooster::myStore())->first();
				if (CRUDBooster::myPrivilegeName() == "Logistics") {
					$query->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status')->where('transport_types_id',1)->distinct();
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
				else{
					$query->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status','pullout.created_date')
					->whereIn('pullout.stores_id',CRUDBooster::myStore())->where('pullout.status','FOR PICK CONFIRM')->distinct();
				}
			}
			else{
			    $query->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status','pullout.created_date')
			    ->where('pullout.status','FOR PICK CONFIRM')->distinct();
			}
	    }
		  
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
			if($column_index == 6){
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
				else if($column_value == "VOID"){
					$column_value = '<span class="label label-danger">VOID</span>';
				}
			}
			if($column_index == 7){
				if($column_value == "Logistics"){
					$column_value = '<span class="label label-default">LOGISTICS</span>';
				}
				elseif($column_value == "Hand Carry"){
					$column_value = '<span class="label label-primary">HAND CARRY</span>';
				}
			}

	    }
		
		public function getDetail($st_number) {

			if(!CRUDBooster::isRead() && $this->global_privilege == false || $this->button_detail == false) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$this->cbLoader();
			$data = array();
			$data['page_title'] = 'Pullout Details';
			$stDetails = Pullout::getDetailWithMoReason($st_number)->get();

			if(count($stDetails) == 0){
				$stDetails = Pullout::getDetailWithSoReason($st_number)->get();
			}

			$data['stDetails'] = $stDetails;
			$data['transfer_from'] = StoreName::find($stDetails[0]->stores_id);
			$data['transfer_to'] = StoreName::getStoreByName($stDetails[0]->wh_to)->first();
			$data['stQuantity'] = Pullout::getItemQty($st_number);
			$items = Pullout::getItems($st_number)->get();

			foreach ($items as $key => $value) {

				$serials = DB::table('serials')->where('pullout_id',$value->id)->select('serial_number')->get();
				$item_detail = DB::table('items')->where('digits_code', $value->item_code)->first();

				$serial_data = array();
				foreach ($serials as $serial) {
					array_push($serial_data, $serial->serial_number);
				}

				$item_data[$key] = [
					'digits_code' => $value->item_code,
					'upc_code' => $item_detail->upc_code,
					'item_description' => $item_detail->item_description,
					'price' => $item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;
			
			$this->cbView("pullout.detail", $data);
		}

		public function getpickConfirm($st_number) {
			$this->cbLoader();
			$data = array();
			$data['page_title'] = 'Pick Confirm Pullout';
			$stDetails = Pullout::getByRef($st_number)->get();
			$data['stDetails'] = $stDetails;
			$data['transfer_from'] = StoreName::find($stDetails[0]->stores_id);
			$data['transfer_to'] = StoreName::getStoreByName($stDetails[0]->wh_to)->first();
			$data['stQuantity'] = Pullout::getItemQty($st_number);
			$items = Pullout::getItems($st_number)->get();

			foreach ($items as $key => $value) {

				$serials = DB::table('serials')->where('pullout_id',$value->id)->select('serial_number')->get();
				$item_detail = DB::table('items')->where('digits_code', $value->item_code)->first();

				$serial_data = array();
				foreach ($serials as $serial) {
					array_push($serial_data, $serial->serial_number);
				}

				$item_data[$key] = [
					'digits_code' => $value->item_code,
					'upc_code' => $item_detail->upc_code,
					'bea_item_id' => $item_detail->bea_item_id,
					'item_description' => $item_detail->item_description,
					'price' => $item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;
			if($stDetails[0]->wh_to == 'DIGITSWAREHOUSE'){
				$this->cbView("pullout.pickconfirm-online-fbd-stw", $data);
			}
		}

		public function saveSTWOnlinePickConfirm(Request $request) {

			$pullout_record = Pullout::getByRef($request->st_number)->first();
			if($pullout_record->status == 'RECEIVED'){
				CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' has already been received!','warning')->send();
			}
			
			// $posItemDetails = array();
			$store = StoreName::getStoreByName($request->transfer_from)->first();
			
			// foreach ($request->digits_code as $key_item => $value_item) {
			// 	$serial = $value_item.'_serial_number';
				
			// 	if(!empty($request->$serial)){
					
			// 		foreach ($request->$serial as $key_serial => $value_serial) {
						
			// 			$posItemDetails[$value_item.'-'.$key_serial] = [
			// 				'item_code' => $value_item,
			// 				'quantity' => 1,
			// 				'serial_number' => $value_serial,
			// 				'item_price' => $request->price[$key_item]
			// 			];
			// 		}
					
			// 	}
			// 	else{
					
			// 		$posItemDetails[$value_item.'-'.$key_item] = [
			// 			'item_code' => $value_item,
			// 			'quantity' => $request->st_quantity[$key_item],
			// 			'item_price' => $request->price[$key_item]
			// 		];
					
			// 	}
			// }
			
			// $postedST = app(POSPushController::class)->posCreateStockTransfer('BEAPOSMW', $request->from_transfer_branch, $request->from_transfer_transit, $request->transfer_to, 'STW-'.$request->customer_name, $posItemDetails);
			
			$record = false;
			$received_st_number = $request->sor_number;
			// $received_st_number = $postedST['data']['record']['fdocument_no'];
			// if($postedST['data']['record']['fresult'] != "ERROR" && !empty($received_st_number)){
				DB::table('pullout')->where('st_document_number', $request->st_number)->update([
					'status' => 'RECEIVED',
					'received_st_number' => $received_st_number,
					'received_st_date' => date('Y-m-d'),
					'pickconfirm_date' => date('Y-m-d'),
					'updated_at' => date('Y-m-d H:i:s'),
					'received_by' => CRUDBooster::myId(),
					'received_at' => date('Y-m-d H:i:s')
				]);

				$record = true;
				try {
					$sorDetails = app(EBSPullController::class)->getSORDetails($request->sor_number);
				} catch (\Exception $e) {
					\Log::error('SOR Detail Error: '.json_encode($e));
				}

				if($store->channel_id == 4 && substr($store->bea_mo_store_name, -3) == 'FBV'){
					app(EBSPushController::class)->dtoReceiving($request->st_number, 263);
				// 	app(POSPushController::class)->posCreateStockAdjustmentOut($received_st_number,'DIGITSWAREHOUSE', $posItemDetails);
				}

				elseif($store->channel_id == 4 && substr($store->bea_mo_store_name, -3) == 'FBD'){
					
					foreach ($request->digits_code as $key_item => $value_item) {
						app(EBSPushController::class)->createSIT($pullout_record->sor_number, 
							$request->bea_item_id[$key_item], 
							$request->st_quantity[$key_item], 
							$request->from_transfer_subinventory,
							'ECOM',
							''
						);
					}

					// app(POSPushController::class)->posCreateStockAdjustmentOut($received_st_number,'DIGITSWAREHOUSE', $posItemDetails);
				}
				
			// }

			if($record && !empty($received_st_number))
                CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' received successfully!','success')->send();
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' failed to be received!','danger')->send();
            }
			
		}

	}
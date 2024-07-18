<?php 

	namespace App\Http\Controllers;

	use Session;
	use DB;
	use CRUDBooster;
	use Illuminate\Http\Request;
	use App\ApprovalMatrix;
	use App\Pullout;
	use App\Serials;
use App\StoreName;

	class AdminPulloutApprovalController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "st_document_number";
			$this->limit = "20";
			$this->orderby = "created_date,asc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "pullout";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"ST/REF #","name"=>"st_document_number"];
			$this->col[] = ["label"=>"MOR/SOR #","name"=>"sor_number"];
			$this->col[] = ["label"=>"From WH","name"=>"stores_id","join"=>"stores,pos_warehouse_name"];
			$this->col[] = ["label"=>"To WH","name"=>"wh_to"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Transport Type","name"=>"transport_types_id","join"=>"transport_types,transport_type"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_date"];
			# END COLUMNS DO NOT REMOVE THIS LINE

	        $this->addaction = array();
			$this->addaction[] = ['title'=>'Approval','url'=>CRUDBooster::mainpath('review').'/[st_document_number]','icon'=>'fa fa-thumbs-up','color'=>'info','showIf'=>"[status]=='PENDING'"];
			$this->addaction[] = ['title'=>'Detail','url'=>CRUDBooster::mainpath().'/details/[st_document_number]','icon'=>'fa fa-eye','color'=>'primary'];
	        
	    }
		
	    public function hook_query_index(&$query) {
	        //Your code here
	        if(CRUDBooster::myPrivilegeName() == "Approver" || CRUDBooster::myPrivilegeName() == "Franchise Approver"){
				//get approval matrix
				$approvalMatrix = ApprovalMatrix::where('approval_matrix.cms_users_id', CRUDBooster::myId())->get();
				
				$approval_array = array();
				foreach($approvalMatrix as $matrix){
					array_push($approval_array, $matrix->store_list);
				}
				$approval_string = implode(",",$approval_array);
				$storeList = array_map('intval',explode(",",$approval_string));

				//compare the store_list of approver to purchase header store_id
				$query->whereIn('pullout.stores_id', array_values((array)$storeList))
					->where('pullout.status', 'PENDING')
					->where('pullout.wh_to','DIGITSRMA')
					->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status')->distinct();
			}
// 			elseif(CRUDBooster::myPrivilegeName() == "Approver" || CRUDBooster::myPrivilegeName() == "Franchise Approver"){
// 				//get approval matrix
// 				$approvalMatrix = ApprovalMatrix::where('approval_matrix.cms_users_id', CRUDBooster::myId())->get();
				
// 				$approval_array = array();
// 				foreach($approvalMatrix as $matrix){
// 					array_push($approval_array, $matrix->store_list);
// 				}
// 				$approval_string = implode(",",$approval_array);
// 				$storeList = array_map('intval',explode(",",$approval_string));

// 				//compare the store_list of approver to purchase header store_id
// 				$query->whereIn('pullout.stores_id', array_values((array)$storeList))
// 				    ->where('pullout.wh_to','DIGITSRMA')
// 					->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status')->distinct();
// 			}
			else{
				$query->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status')
				->where('pullout.wh_to','DIGITSRMA')
				->where('pullout.status','PENDING')->distinct();
			} 
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
			if($column_index == 4){
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
				else if($column_value == "RECEIVED"){
					$column_value = '<span class="label label-success">RECEIVED</span>';
				}
				else if($column_value == "VOID"){
					$column_value = '<span class="label label-danger">VOID</span>';
				}
			}
			if($column_index == 5){
				if($column_value == "Logistics"){
					$column_value = '<span class="label label-default">LOGISTICS</span>';
				}
				elseif($column_value == "Hand Carry"){
					$column_value = '<span class="label label-primary">HAND CARRY</span>';
				}
			}
	    }
		
		public function getApproval($st_number)
		{
			if(!CRUDBooster::isSuperadmin() && CRUDBooster::myPrivilegeName() != "Approver") {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$this->cbLoader();
			$data = array();
			$data['page_title'] = 'Review Pullout Details';
			$stDetails = Pullout::getDetailWithMoReason($st_number)->get();
			
			if(count($stDetails) == 0){
				$stDetails = Pullout::getDetailWithSoReason($st_number)->get();
			}
			$data['stDetails'] = $stDetails;
			$data['transfer_from'] = StoreName::find($stDetails[0]->stores_id);
			$data['transfer_to'] = StoreName::getStoreByName($stDetails[0]->wh_to)->first();
			$items = Pullout::with(['item','itemUpc','serial'])->getItemsForApproval($st_number)->get();
			$data['stQuantity'] =  Pullout::getItemQty($st_number);
			$item_data = [];
			foreach ($items as $key => $value) {
				$item_detail = $value->itemUpc;
				if(!$value->request_type){
					$item_detail = $value->item;
				}
				
				$serial_data = array();
				foreach ($value->serial ?? [] as $serial) {
					array_push($serial_data, $serial->serial_number);
				}

				$item_data[$key] = [
					'digits_code' => $value->item_code,
					'upc_code' => $item_detail->upc_code,
					'bea_item_id' => $item_detail->bea_item_id,
					'item_description' => $item_detail != NULL ? $item_detail->item_description : $value->item_description,
					'price' => $item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;
			$data['action_url'] = route('saveReviewPullout');
			$this->cbView("pullout.approval", $data);
			
		}

		public function saveReviewPullout(Request $request)
		{
			$isGisSt = DB::table('pullout')->where('st_document_number',$request->st_number)->first();
			if(!$isGisSt->request_type){
				$pullout_approval = Pullout::where('st_document_number',$request->st_number)->first();
				if($pullout_approval->step > 3){
					CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' has already been updated!','warning')->send();
				}
				if($pullout_approval->status != 'PENDING'){
					CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' has already been updated!','warning')->send();
				}
				if($request->approval_action == 1){ // approve
					
					$pullout_status = array();
					$store = DB::table('stores')->where('pos_warehouse',$request->transfer_from)->where('status','ACTIVE')->first();
					$customer = app(EBSPullController::class)->getPriceList($store->bea_so_store_name);
					$pullout = Pullout::where('st_document_number',$request->st_number)->first();

					if(in_array($request->channel_id, [1,6])){ //retail
						foreach ($request->digits_code as $key_item => $value_item) {
							// add bea mor/sor interface creation
					// 		if(!empty(app(EBSPullController::class)->getCreatedMOR($request->st_number))){
					// 		    CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' has already been updated!','warning')->send();
					// 		}
							if($request->transaction_type == 'RMA'){
								if(empty(app(EBSPullController::class)->getCreatedItemMOR($request->st_number,$request->bea_item[$key_item]))){
									app(EBSPushController::class)->createMOR($request->bea_item[$key_item], $store->doo_subinventory, ($request->st_quantity[$key_item])*(-1), 'TO CHECK', $request->st_number, 225, $request->reason_mo, 223);
								}
								$pullout_status = app(StatusWorkflowController::class)->getRMANextStatus(
									$pullout->channel_id, 
									$pullout->transport_types_id, 
									'',
									$pullout->step);
							}
							elseif($request->transaction_type == 'STW'){
								if(empty(app(EBSPullController::class)->getCreatedItemMOR($request->st_number,$request->bea_item[$key_item]))){
									app(EBSPushController::class)->createMOR($request->bea_item[$key_item], $store->doo_subinventory, ($request->st_quantity[$key_item])*(-1), $store->org_subinventory, $request->st_number, 224, $request->reason_mo, 223);
								}
								$pullout_status = app(StatusWorkflowController::class)->getSTWNextStatus(
									$pullout->channel_id, 
									$pullout->transport_types_id, 
									'',
									$pullout->step);
							}
						}
					}

					elseif($request->channel_id == 4 && substr($store->bea_mo_store_name, -3) == 'FBV'){
						
						
						if($request->transaction_type == 'RMA'){
							foreach ($request->digits_code as $key_item => $value_item) {
							//  if(!empty(app(EBSPullController::class)->getCreatedMOR($request->st_number))){
						// 		    CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' has already been updated!','warning')->send();
						// 		}
								if(empty(app(EBSPullController::class)->getCreatedItemMOR($request->st_number,$request->bea_item[$key_item]))){
									app(EBSPushController::class)->createMOR($request->bea_item[$key_item], $store->doo_subinventory, ($request->st_quantity[$key_item])*(-1), 'TO CHECK', $request->st_number, 225, $request->reason_mo, 223);
								}
							}
							$pullout_status = app(StatusWorkflowController::class)->getOnlineRMANextStatus(
								$pullout->channel_id, 
								substr($store->bea_mo_store_name, -3),
								$pullout->step);
						}
						else{
							foreach ($request->digits_code as $key_item => $value_item) {
							//  if(!empty(app(EBSPullController::class)->getCreatedMOR($request->st_number))){
						// 		    CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' has already been updated!','warning')->send();
						// 		}
								if(empty(app(EBSPullController::class)->getCreatedItemMOR($request->st_number,$request->bea_item[$key_item]))){
									app(EBSPushController::class)->createMOR($request->bea_item[$key_item], $store->doo_subinventory, ($request->st_quantity[$key_item])*(-1), $store->org_subinventory, $request->st_number, 263, $request->reason_mo, 223);
								}
							}
		
							$pullout_status = app(StatusWorkflowController::class)->getOnlineSTWNextStatus(
								$pullout->channel_id,
								substr($store->bea_mo_store_name, -3),
								$pullout->step);
						}
						
					}

					elseif($request->channel_id == 4 && substr($store->bea_mo_store_name, -3) == 'FBD'){ 

						if($request->transaction_type == 'RMA'){

							$rmaHeader = app(EBSPushController::class)->sorRMAHeaders($customer->price_list_id, $customer->sold_to_org_id, $customer->ship_to_org_id, $customer->invoice_to_org_id, $request->st_number);
							foreach ($request->digits_code as $key_item => $value_item) {
								$line_number = $key_item;
								$line_number++;
								app(EBSPushController::class)->sorRMALines($line_number, $rmaHeader['rma_header'], $request->bea_item[$key_item], $request->st_quantity[$key_item], $customer->price_list_id, $request->price[$key_item], $request->price[$key_item], $request->reason_so, 'TO CHECK');
							}

							$pullout_status = app(StatusWorkflowController::class)->getOnlineRMANextStatus(
								$pullout->channel_id, 
								substr($store->bea_mo_store_name, -3),
								$pullout->step);
						}
						else{
							$pullout_status = app(StatusWorkflowController::class)->getOnlineSTWNextStatus(
								$pullout->channel_id,  
								substr($store->bea_mo_store_name, -3),
								$pullout->step);
						}
					
					}

					elseif(in_array($request->channel_id, [2,7,10,11]) && $request->transaction_type == 'RMA'){ //fra

						$rmaHeader = app(EBSPushController::class)->sorRMAHeaders($customer->price_list_id, $customer->sold_to_org_id, $customer->ship_to_org_id, $customer->invoice_to_org_id, $request->st_number);
						foreach ($request->digits_code as $key_item => $value_item) {
							$line_number = $key_item;
							$line_number++;
							app(EBSPushController::class)->sorRMALines($line_number, $rmaHeader['rma_header'], $request->bea_item[$key_item], $request->st_quantity[$key_item], $customer->price_list_id, $request->price[$key_item], $request->price[$key_item], $request->reason_so, 'TO CHECK');
						}

						$pullout_status = app(StatusWorkflowController::class)->getRMANextStatus(
							$pullout->channel_id, 
							$pullout->transport_types_id, 
							'',
							$pullout->step);
					}

					elseif(in_array($request->channel_id, [2,7,10,11]) && $request->transaction_type == 'STW'){ //fra
						
						if(substr($request->digits_code[0], 0 , 1) == '7'){
							foreach ($request->digits_code as $key_item => $value_item) {
								if(!empty(app(EBSPullController::class)->getCreatedMOR($request->st_number))){
									CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' has already been updated!','warning')->send();
								}
								app(EBSPushController::class)->createMOR($request->bea_item[$key_item], $store->doo_subinventory, ($request->st_quantity[$key_item])*(-1), $store->org_subinventory, $request->st_number, 224, $request->reason_mo, 223);
							}
						}
						else{
							$sorHeader = app(EBSPushController::class)->sorHeaders($customer->price_list_id, 224, $customer->sold_to_org_id, $customer->ship_to_org_id, $customer->invoice_to_org_id, $request->st_number);
							foreach ($request->digits_code as $key_item => $value_item) {
								$line_number = $key_item;
								$line_number++;
								app(EBSPushController::class)->sorLines($line_number, $sorHeader['sor_header'], $request->bea_item[$key_item], $request->st_quantity[$key_item], $customer->price_list_id, $request->price[$key_item], $request->price[$key_item], $request->reason_so, 224, $store->org_subinventory);
							}
						}

						$pullout_status = app(StatusWorkflowController::class)->getSTWNextStatus(
							$pullout->channel_id, 
							$pullout->transport_types_id, 
							'',
							$pullout->step);
						
					}	

					Pullout::where('st_document_number',$request->st_number)->update([
						'status' =>  $pullout_status->workflow_status,
						'step' => $pullout_status->next_step, 
						'approved_at' => date('Y-m-d H:i:s'),
						'approved_by' => CRUDBooster::myId(),
						'updated_at' => date('Y-m-d H:i:s')
					]);

					CRUDBooster::redirect(CRUDBooster::mainpath(),'ST#'.$request->st_number.' has been approved!','success')->send();
				}
				else{
					//void st
					if(substr($request->st_number,3) != "REF"){
					
						$voidST = app(POSPushController::class)->voidStockTransfer($request->st_number);
						\Log::info('void st: '.json_encode($voidST));

						if($voidST['data']['record']['fresult'] == "ERROR"){
							$error = $voidST['data']['record']['errors']['error'];
							CRUDBooster::redirect(CRUDBooster::mainpath(),'Fail! '.$error,'warning')->send();
						}
					}

					Pullout::where('st_document_number',$request->st_number)->update([
						'status' => 'VOID',
						'rejected_at' => date('Y-m-d H:i:s'),
						'rejected_by' => CRUDBooster::myId(),
						'updated_at' => date('Y-m-d H:i:s')
					]);
					CRUDBooster::redirect(CRUDBooster::mainpath(),'ST#'.$request->st_number.' has been rejected!','info')->send();
				}
			}else{
				if($request->approval_action  == 1){
					$pullout_status = array();
					$store = DB::table('stores')->where('pos_warehouse',$request->transfer_from)->where('status','ACTIVE')->first();
					$customer = app(EBSPullController::class)->getPriceList($store->bea_so_store_name);
					$pullout = Pullout::where('st_document_number',$request->st_number)->first();

					$rmaHeader = app(EBSPushController::class)->sorRMAHeaders($customer->price_list_id, $customer->sold_to_org_id, $customer->ship_to_org_id, $customer->invoice_to_org_id, $request->st_number);
					foreach ($request->digits_code as $key_item => $value_item) {
						$line_number = $key_item;
						$line_number++;
						app(EBSPushController::class)->sorRMALines($line_number, $rmaHeader['rma_header'], $request->bea_item[$key_item], $request->st_quantity[$key_item], $customer->price_list_id, $request->price[$key_item], $request->price[$key_item], $request->reason_so, 'TO CHECK');
					}

					$pullout_status = app(StatusWorkflowController::class)->getRMANextStatus(
						$pullout->channel_id, 
						$pullout->transport_types_id, 
						'',
						$pullout->step);

					Pullout::where('st_document_number',$request->st_number)->update([
						'status' =>  $pullout_status->workflow_status,
						'step' => $pullout_status->next_step, 
						'approved_at' => date('Y-m-d H:i:s'),
						'approved_by' => CRUDBooster::myId(),
						'updated_at' => date('Y-m-d H:i:s')
					]);
					CRUDBooster::redirect(CRUDBooster::mainpath(),''.$request->st_number.' has been approved!','success')->send();
				}else{
					$items = DB::table('pullout')->where('st_document_number',$request->st_number)->get();
					$mw_location = DB::table('stores')->where('id',$isGisSt->stores_id)->first();
					//get location
					$location = DB::connection('gis')->table('locations')->where('status','ACTIVE')
					->where('location_name',$mw_location->bea_so_store_name)->first();
		
					//get sublocation
					$sublocation = DB::connection('gis')->table('sub_locations')->where('status','ACTIVE')
					->where('location_id',$location->id)->where('description','STOCK ROOM(D)')->first();
					//get sub location in transit
					$from_intransit_gis_sub_location = DB::connection('gis')->table('sub_locations')->where('status','ACTIVE')
					->where('location_id',$location->id)->where('description','IN TRANSIT')->first();
					Pullout::where('st_document_number',$request->st_number)->update([
						'status' => 'VOID',
						'rejected_at' => date('Y-m-d H:i:s'),
						'rejected_by' => CRUDBooster::myId(),
						'updated_at' => date('Y-m-d H:i:s')
					]);
	
					//REVERT QTY IN GIS INVENTORY LINES
					foreach($items as $key => $item){
						DB::connection('gis')->table('inventory_capsules')
						->leftjoin('inventory_capsule_lines','inventory_capsules.id','inventory_capsule_lines.inventory_capsules_id')
						->leftjoin('items','inventory_capsules.item_code','items.digits_code2')
						->where([
							'items.digits_code' => $item->item_code,
							'inventory_capsules.locations_id' => $location->id
						])
						->where('inventory_capsule_lines.sub_locations_id',$sublocation->id)
						->update([
							'qty' => DB::raw("qty + $item->quantity"),
							'inventory_capsule_lines.updated_at' => date('Y-m-d H:i:s')
						]);
						//ADD GIS MOVEMENT HISTORY
						//get item code
						$gis_mw_name = DB::connection('gis')->table('cms_users')->where('email','mw@gashapon.ph')->first();
						$item_code = DB::connection('gis')->table('items')->where('digits_code',$item->item_code)->first();
						$capsuleAction = DB::connection('gis')->table('capsule_action_types')->where('status','ACTIVE')
						->where('description','REJECTED')->first();
						DB::connection('gis')->table('history_capsules')->insert([
							'reference_number' => $request->st_number,
							'item_code' => $item_code->digits_code2,
							'capsule_action_types_id' => $capsuleAction->id,
							'locations_id' => $location->id,
							'from_sub_locations_id' => $sublocation->id,
							'qty' => $item->quantity,
							'created_at' => date('Y-m-d H:i:s'),
							'created_by' => $gis_mw_name->id
						]);
					}
					CRUDBooster::redirect(CRUDBooster::mainpath(),''.$request->st_number.' has been rejected!','info')->send();
				}
			}
		}

		public function saveReviewMarketingPullout(Request $request)
		{
			if($request->approval_action == 1){ // approve
				Pullout::where('st_document_number',$request->st_number)->update([
					'status' => ($request->transport_type == 'Logistics') ? 'FOR SCHEDULE' : 'FOR RECEIVING',
					'approved_at' => date('Y-m-d H:i:s'),
					'approved_by' => CRUDBooster::myId(),
					'updated_at' => date('Y-m-d H:i:s')
				]);

				CRUDBooster::redirect(CRUDBooster::mainpath(),'ST#'.$request->st_number.' has been approved!','success')->send();
			}
			else{
				
				Pullout::where('st_document_number',$request->st_number)->update([
					'status' => 'REJECTED',
					'rejected_at' => date('Y-m-d H:i:s'),
					'rejected_by' => CRUDBooster::myId(),
					'updated_at' => date('Y-m-d H:i:s')
				]);
				CRUDBooster::redirect(CRUDBooster::mainpath(),'ST#'.$request->st_number.' has been rejected!','info')->send();
				
			}
		}

		public function getDetail($st_number)
		{
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

			$items = Pullout::with(['item','serial'])->getItemsForApproval($st_number)->get();
			$data['stQuantity'] = Pullout::getItemQty($st_number);

			foreach ($items as $key => $value) {

				$serial_data = array();
				foreach ($value->serial ?? [] as $serial) {
					array_push($serial_data, $serial->serial_number);
				}

				$item_data[$key] = [
					'digits_code' => $value->item_code,
					'upc_code' => $value->item->upc_code,
					'brand' => $value->item->brand,
					'item_description' => $value->item != NULL ? $value->item->item_description : $value->item_description,
					'price' => $value->item->store_cost,
					'problems' => $value->problems.' : '.$value->problem_detail,
					'st_quantity' => $value->quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;
			
			$this->cbView("pullout.detail", $data);
		}
	}
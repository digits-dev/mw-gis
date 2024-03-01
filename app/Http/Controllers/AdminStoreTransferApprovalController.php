<?php namespace App\Http\Controllers;

	use Session;
	use DB;
	use CRUDBooster;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\File;
	use Illuminate\Http\Request;
	use App\ApprovalMatrix;

	class AdminStoreTransferApprovalController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->table = "pos_pull";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"ST #","name"=>"st_document_number"];
			$this->col[] = ["label"=>"From WH","name"=>"stores_id","join"=>"stores,pos_warehouse_name"];
			$this->col[] = ["label"=>"To WH","name"=>"stores_id_destination","join"=>"stores,pos_warehouse_name"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Transport Type","name"=>"transport_types_id","join"=>"transport_types,transport_type"];
			$this->col[] = ["label"=>"Reason","name"=>"reason_id","join"=>"reason,pullout_reason"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_date"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();
			$this->addaction[] = ['title'=>'Approval','url'=>CRUDBooster::mainpath('review').'/[st_document_number]','icon'=>'fa fa-thumbs-up','color'=>'info','showIf'=>"[status]=='PENDING'"];
			$this->addaction[] = ['title'=>'Details','url'=>CRUDBooster::mainpath('details').'/[st_document_number]','icon'=>'fa fa-eye','color'=>'primary'];
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();



	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
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
				$query->whereIn('pos_pull.stores_id', array_values((array)$storeList))
					->where('pos_pull.status', 'PENDING')
					->select('pos_pull.st_document_number','pos_pull.wh_from','pos_pull.wh_to','pos_pull.status')->distinct();
			}
			else{
				$query->select('pos_pull.st_document_number','pos_pull.wh_from','pos_pull.wh_to','pos_pull.status')
				->where('pos_pull.status','PENDING')->distinct();
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
			if($column_index == 3){
				
				if($column_value == "PENDING"){
					$column_value = '<span class="label label-warning">PENDING</span>';
				}
				else if($column_value == "FOR PICKLIST"){
					$column_value = '<span class="label label-warning">FOR PICKLIST</span>';
				}
				else if($column_value == "FOR PICK CONFIRM"){
					$column_value = '<span class="label label-warning">FOR PICK CONFIRM</span>';
				}
				else if($column_value == "RECEIVED"){
					$column_value = '<span class="label label-success">RECEIVED</span>';
				}
				else if($column_value == "VOID"){
					$column_value = '<span class="label label-danger">VOID</span>';
				}
			}
			if($column_index == 4){
				if($column_value == "Logistics"){
					$column_value = '<span class="label label-default">LOGISTICS</span>';
				}
				elseif($column_value == "Hand Carry"){
					$column_value = '<span class="label label-primary">HAND CARRY</span>';
				}
			}
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

		public function getApproval($st_number)
		{
			if(!CRUDBooster::isSuperadmin() && CRUDBooster::myPrivilegeName() != "Approver") {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$this->cbLoader();

			$data = array();

			$data['page_title'] = 'Review Stock Transfer Details';

			$data['stDetails'] = DB::table('pos_pull')
				->join('reason', 'pos_pull.reason_id', '=', 'reason.id')
				->leftJoin('transport_types', 'pos_pull.transport_types_id', '=', 'transport_types.id')
				->where('pos_pull.st_document_number', $st_number)
				->select('pos_pull.*','reason.pullout_reason','transport_types.transport_type')
				->get();
			

			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id)->first();

			$data['transfer_to'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id_destination)->first();

			$items = DB::table('pos_pull')
				->where('st_document_number',$st_number)
				->select('id','item_code','quantity','item_description')
				->get();

			$data['stQuantity'] =  DB::table('pos_pull')
				->where('st_document_number', $st_number)
				->sum('quantity');

			foreach ($items as $key => $value) {

				$serials = DB::table('serials')->where('pos_pull_id',$value->id)->select('serial_number')->get();
				$item_detail = DB::table('items')->where('digits_code', $value->item_code)->first();

				$serial_data = array();
				foreach ($serials as $serial) {
					array_push($serial_data, $serial->serial_number);
				}

				$item_data[$key] = [
					'digits_code' => $value->item_code,
					'upc_code' => $item_detail->upc_code,
					'item_description' => $item_detail->item_description != NULL ? $item_detail->item_description : $value->item_description,
					'price' => $item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;
			//dd($data);
			$this->cbView("stock-transfer.approval", $data);
		}

		public function saveReviewST(Request $request)
		{
			$isGisSt = DB::table('pos_pull')->where('st_document_number',$request->st_number)->first();
			if(!$isGisSt->location_id_from){
				if($request->approval_action == 1){ // approve
					if(CRUDBooster::myChannel() == 4){
						DB::table('pos_pull')->where('st_document_number',$request->st_number)->update([
							'status' => 'FOR PICKLIST',
							'approved_at' => date('Y-m-d H:i:s'),
							'approved_by' => CRUDBooster::myId(),
							'updated_at' => date('Y-m-d H:i:s')
						]);
					}
					else{
						DB::table('pos_pull')->where('st_document_number',$request->st_number)->update([
							'status' => ($request->transport_type == 'Logistics') ? 'FOR SCHEDULE' : 'FOR RECEIVING',
							'approved_at' => date('Y-m-d H:i:s'),
							'approved_by' => CRUDBooster::myId(),
							'updated_at' => date('Y-m-d H:i:s')
						]);
					}
					
					CRUDBooster::redirect(CRUDBooster::mainpath(),'ST#'.$request->st_number.' has been approved!','success')->send();
				}else{
					//void st
					if(substr($request->st_number,3) != "REF"){
						$voidST = app(POSPushController::class)->voidStockTransfer($request->st_number);
						\Log::info('void st: '.json_encode($voidST));
	
						if($voidST['data']['record']['fresult'] == "ERROR"){
							$error = $voidST['data']['record']['errors']['error'];
							CRUDBooster::redirect(CRUDBooster::mainpath(),'Fail! '.$error,'warning')->send();
						}
					}
					DB::table('pos_pull')->where('st_document_number',$request->st_number)->update([
						'status' => 'VOID',
						'rejected_at' => date('Y-m-d H:i:s'),
						'rejected_by' => CRUDBooster::myId(),
						'updated_at' => date('Y-m-d H:i:s')
					]);
					CRUDBooster::redirect(CRUDBooster::mainpath(),'ST#'.$request->st_number.' has been rejected!','info')->send();
					
				}
			}else{
				if($request->approval_action  == 1){
					DB::table('pos_pull')->where('st_document_number',$request->st_number)->update([
						'status' => ($request->transport_type == 'Logistics') ? 'FOR SCHEDULE' : 'FOR RECEIVING',
						'approved_at' => date('Y-m-d H:i:s'),
						'approved_by' => CRUDBooster::myId(),
						'updated_at' => date('Y-m-d H:i:s')
					]);
					CRUDBooster::redirect(CRUDBooster::mainpath(),''.$request->st_number.' has been approved!','success')->send();
				}else{
					$items = DB::table('pos_pull')->where('st_document_number',$request->st_number)->get();
					$from_intransit_gis_sub_location = DB::connection('gis')->table('sub_locations')->where('status','ACTIVE')
					->where('location_id',$isGisSt->location_id_from)->where('description','IN TRANSIT')->first();
					DB::table('pos_pull')->where('st_document_number',$request->st_number)->update([
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
							'inventory_capsules.locations_id' => $item->location_id_from
						])
						->where('inventory_capsule_lines.sub_locations_id',$item->sub_location_id_from)
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
							'locations_id' => $item->location_id_from,
							'from_sub_locations_id' => $item->sub_location_id_from,
							'to_sub_locations_id' => $from_intransit_gis_sub_location->id,
							'qty' => $item->quantity,
							'created_at' => date('Y-m-d H:i:s'),
							'created_by' => $gis_mw_name->id
						]);
						DB::connection('gis')->table('history_capsules')->insert([
							'reference_number' => $request->st_number,
							'item_code' => $item_code->digits_code2,
							'capsule_action_types_id' => $capsuleAction->id,
							'locations_id' => $item->location_id_from,
							'from_sub_locations_id' => $from_intransit_gis_sub_location->id,
							'to_sub_locations_id' => $item->sub_location_id_from,
							'qty' => -1 * abs($item->quantity),
							'created_at' => date('Y-m-d H:i:s'),
							'created_by' => $gis_mw_name->id
						]);
					}
					CRUDBooster::redirect(CRUDBooster::mainpath(),''.$request->st_number.' has been rejected!','info')->send();
				}
			}
			
		}

		public function getDetail($st_number)
		{
			if(!CRUDBooster::isRead() && $this->global_privilege == false || $this->button_detail == false) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$this->cbLoader();

			$data = array();

			$data['page_title'] = 'Stock Transfer Details';

			$data['stDetails'] = DB::table('pos_pull')
				->join('reason', 'pos_pull.reason_id', '=', 'reason.id')
				->leftJoin('transport_types', 'pos_pull.transport_types_id', '=', 'transport_types.id')
				->where('st_document_number', $st_number)
				->select('pos_pull.*','reason.pullout_reason','transport_types.transport_type')
				->get();
			

			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id)->first();

			$data['transfer_to'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id_destination)->first();

			$items = DB::table('pos_pull')
				->where('st_document_number',$st_number)
				->select('id','item_code','quantity','item_description')
				->get();

			$data['stQuantity'] =  DB::table('pos_pull')
				->where('st_document_number', $st_number)
				->sum('quantity');

			foreach ($items as $key => $value) {

				$serials = DB::table('serials')->where('pos_pull_id',$value->id)->select('serial_number')->get();
				$item_detail = DB::table('items')->where('digits_code', $value->item_code)->first();

				$serial_data = array();
				foreach ($serials as $serial) {
					array_push($serial_data, $serial->serial_number);
				}

				$item_data[$key] = [
					'digits_code' => $value->item_code,
					'upc_code' => $item_detail->upc_code,
					'item_description' => $item_detail->item_description != NULL ? $item_detail->item_description : $value->item_description,
					'price' => $item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;
			
			$this->cbView("stock-transfer.detail", $data);
		}
	}
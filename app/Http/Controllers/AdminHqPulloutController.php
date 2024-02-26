<?php namespace App\Http\Controllers;

	use Session;
	use DB;
	use CRUDBooster;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\File;
	use Illuminate\Http\Request;
	use App\ApprovalMatrix;

	class AdminHqPulloutController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {
	    	# START CONFIGURATION DO NOT REMOVE THIS LINE

			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
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
			$this->table = "pullout";

			# END CONFIGURATION DO NOT REMOVE THIS LINE						      

			# START COLUMNS DO NOT REMOVE THIS LINE
	        $this->col = [];
			$this->col[] = ["label"=>"ST #","name"=>"st_document_number"];
			$this->col[] = ["label"=>"Received ST #","name"=>"received_st_number"];
			$this->col[] = ["label"=>"BEA #","name"=>"sor_number"];
			$this->col[] = ["label"=>"From WH","name"=>"wh_from"];
			$this->col[] = ["label"=>"To WH","name"=>"wh_to"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];

			# END COLUMNS DO NOT REMOVE THIS LINE
			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			
			# END FORM DO NOT REMOVE THIS LINE     

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
			if(!in_array(CRUDBooster::myPrivilegeName(),["Logistics","Warehouse","RMA"])){
				$this->addaction[] = ['title'=>'Void ST','url'=>CRUDBooster::mainpath('void-st').'/[st_document_number]','icon'=>'fa fa-times','color'=>'danger','showIf'=>"[status]=='PENDING'"];
			}	
			if(CRUDBooster::isSuperadmin()){
				$this->addaction[] = ['title'=>'Re-run Create ST','url'=>CRUDBooster::mainpath('create-st'),'icon'=>'fa fa-refresh','color'=>'success','showIf'=>"[st_document_number]==''"];
				$this->addaction[] = ['title'=>'Re-run Receive ST','url'=>CRUDBooster::mainpath('received-st').'/[st_document_number]','icon'=>'fa fa-refresh','color'=>'info','showIf'=>"[recst_number]=='' and [status]!='VOID'"];
			}
			//$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('print').'/[st_document_number]','icon'=>'fa fa-print','color'=>'info','showIf'=>"[status]=='FOR SCHEDULE' and [sor_number]!=''"];
			if(CRUDBooster::isSuperadmin() || CRUDBooster::myPrivilegeName() == "Logistics"){
				$this->addaction[] = ['title'=>'Schedule','url'=>CRUDBooster::mainpath('schedule').'/[st_document_number]','icon'=>'fa fa-calendar','color'=>'primary','showIf'=>"[status]=='FOR SCHEDULE' and [sor_number]!=''"];
			}
			$this->addaction[] = ['title'=>'Detail','url'=>CRUDBooster::mainpath('details').'/[st_document_number]','icon'=>'fa fa-eye','color'=>'primary'];
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
			if(CRUDBooster::getCurrentMethod() == 'getIndex' && !in_array(CRUDBooster::myPrivilegeName(),["Logistics","Approver","Warehouse"])){
				$this->index_button[] = ['label'=>'Create STW','url'=>CRUDBooster::mainpath().'/stw/create','icon'=>'fa fa-plus','color'=>'success'];
				if(CRUDBooster::myChannel() != 4){ //online & marketing
					$this->index_button[] = ['label'=>'Create ST RMA','url'=>CRUDBooster::mainpath().'/rma/create','icon'=>'fa fa-plus','color'=>'success'];
					//$this->index_button[] = ['label'=>'Create STW Marketing','url'=>CRUDBooster::mainpath().'/stw-marketing/create','icon'=>'fa fa-plus','color'=>'warning'];
				}
				
			}

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
	        if(!CRUDBooster::isSuperadmin()){
				$store = DB::table('stores')->where('id', CRUDBooster::myStore())->first();
				if (CRUDBooster::myPrivilegeName() == "Logistics") {
					$query->select('st_document_number','wh_from','wh_to','status')->distinct();
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
	
					$query->whereIn('stores_id', array_values((array)$storeList))
						->select('st_document_number','wh_from','wh_to','status')->distinct();
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Warehouse","RMA"])){
					$query->select('st_document_number','wh_from','wh_to','status','created_at')
					->where('wh_to',$store->pos_warehouse)->distinct();
				}
				else{
					$query->select('st_document_number','wh_from','wh_to','status','created_at')
					->where('wh_from',$store->pos_warehouse)->distinct();
				}
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

		public function getRMA()
		{
			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'Create ST RMA';

			if(CRUDBooster::isSuperadmin()){
				$data['transfer_from'] = DB::table('stores')
				->select('pos_warehouse','pos_warehouse_transit','pos_warehouse_name')->get();
			}
			else{
				$data['transfer_from'] = DB::table('stores')
				->select('pos_warehouse','pos_warehouse_transit','pos_warehouse_name')
				->where('id',CRUDBooster::myStore())->get();
			}
			

			$data['transfer_to'] = DB::table('stores')
				->select('pos_warehouse','pos_warehouse_name')
				->where('id','!=',CRUDBooster::myStore())
				->where('pos_warehouse','DIGITSRMA')
				->where('status','ACTIVE')
				->get();

			$data['transport_types'] = DB::table('transport_types')
				->select('id','transport_type')
				->where('status','ACTIVE')
				->get();
			
			if(CRUDBooster::myChannel() ==1){ //retail
				$data['reasons'] = DB::table('reason')
				->select('bea_mo_reason as bea_reason','pullout_reason')
				->where('transaction_type_id',2) //rma
				->where('status','ACTIVE')
				->get();
			}
			else{
				$data['reasons'] = DB::table('reason')
				->select('bea_so_reason as bea_reason','pullout_reason')
				->where('transaction_type_id',2) //rma
				->where('status','ACTIVE')
				->get();
			}
			
			
			$data['transfer_org'] = 225;
				
			$this->cbView("pullout.rma-scan", $data);
		}

		public function getSTW()
		{
			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'Create STW';

			if(CRUDBooster::isSuperadmin()){
				$data['transfer_from'] = DB::table('stores')
				->select('pos_warehouse','pos_warehouse_transit','pos_warehouse_name')->get();
			}
			else{
				$data['transfer_from'] = DB::table('stores')
				->select('pos_warehouse','pos_warehouse_transit','pos_warehouse_name')
				->where('id',CRUDBooster::myStore())->get();
			}
			

			$data['transfer_to'] = DB::table('stores')
				->select('pos_warehouse','pos_warehouse_name')
				->where('id','!=',CRUDBooster::myStore())
				->where('pos_warehouse','DIGITSWAREHOUSE')
				->where('status','ACTIVE')
				->get();

			$data['transport_types'] = DB::table('transport_types')
				->select('id','transport_type')
				->where('status','ACTIVE')
				->get();
			
			if(CRUDBooster::myChannel() == 1){ //retail
				$data['reasons'] = DB::table('reason')
				->select('bea_mo_reason as bea_reason','pullout_reason')
				->where('transaction_type_id',1) //STW
				->where('status','ACTIVE')
				->get();
			}
			else{
				$data['reasons'] = DB::table('reason')
				->select('bea_so_reason as bea_reason','pullout_reason')
				->where('transaction_type_id',1) //STW
				->where('status','ACTIVE')
				->get();
			}

			$data['transfer_org'] = 224;
			
			$this->cbView("pullout.stw-scan", $data);
			
		}

		public function getSTWMarketing()
		{
			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'Create STW Marketing';

			if(CRUDBooster::isSuperadmin()){
				$data['transfer_from'] = DB::table('stores')
				->select('pos_warehouse','pos_warehouse_transit','pos_warehouse_name')->get();
			}
			else{
				$data['transfer_from'] = DB::table('stores')
				->select('pos_warehouse','pos_warehouse_transit','pos_warehouse_name')
				->where('id',CRUDBooster::myStore())->get();
			}
			

			$data['transfer_to'] = DB::table('stores')
				->select('pos_warehouse','pos_warehouse_name')
				->where('id','!=',CRUDBooster::myStore())
				->where('pos_warehouse','DIGITSWAREHOUSE')
				->where('status','ACTIVE')
				->get();

			$data['transport_types'] = DB::table('transport_types')
				->select('id','transport_type')
				->where('status','ACTIVE')
				->get();
			
			if(CRUDBooster::myChannel() == 1){ //retail
				$data['reasons'] = DB::table('reason')
				->select('bea_mo_reason as bea_reason','pullout_reason')
				->where('transaction_type_id',3) //STW
				->where('status','ACTIVE')
				->get();
			}
			else{
				$data['reasons'] = DB::table('reason')
				->select('bea_so_reason as bea_reason','pullout_reason')
				->where('transaction_type_id',3) //STW
				->where('status','ACTIVE')
				->get();
			}

			$data['transfer_org'] = 224;
			
			$this->cbView("pullout.stw-marketing-scan", $data);
			
		}

		public function saveCreateSTW(Request $request)
		{

			$validator = \Validator::make($request->all(), [
				'transport_type' => 'required',
				'digits_code' => 'required',
				'transfer_from' => 'required',
				'transfer_to' => 'required',
				'reason' => 'required',
			],
			[
				'transport_type.required' => 'You have to choose transport by.',
				'digits_code.required' => 'You have to add pullout items.',
				'transfer_to.required' => 'You have to choose pullout to store/warehouse.',
				'reason.required' => 'You have to choose pullout reason.',
			]);
	
			if ($validator->fails()) {
				return redirect()->back()
				->withErrors($validator)
				->withInput();
			}

			$posItemDetails = array();
			$stDetails = array();
			// $st_number = '';

			// $store = DB::table('stores')->where('pos_warehouse',$request->transfer_from)->first();
			// $customer = app(EBSPullController::class)->getPriceList($store->bea_so_store_name);
			// if(CRUDBooster::myPrivilegeName() != 'Marketing'){
				foreach ($request->digits_code as $key_item => $value_item) {
					$serial = $value_item.'_serial_number';
					
					if(!empty($request->$serial)){
						
						foreach ($request->$serial as $key_serial => $value_serial) {
							
							$posItemDetails[$value_item.'-'.$key_serial] = [
								'item_code' => $value_item,
								'quantity' => 1,
								'serial_number' => $value_serial,
								'item_price' => $request->price[$key_item]
							];
						}
						
					}
					else{
						
						$posItemDetails[$value_item.'-'.$key_item] = [
							'item_code' => $value_item,
							'quantity' => $request->st_quantity[$key_item],
							'item_price' => $request->price[$key_item]
						];
						
					}
				}
				//dd($posItemDetails);
				$postedST = app(POSPushController::class)->posCreateStockTransfer('BEAPOSMW', $request->transfer_branch, $request->transfer_from, $request->transfer_transit, $request->memo, $posItemDetails);
				\Log::info('create STW: '.json_encode($postedST));

				$st_number = $postedST['data']['record']['fdocument_no'];
			// }
			$record=false;
			//save ST
			foreach ($request->digits_code as $key_item => $value_item) {
				$serial = $value_item.'_serial_number';
				$item_serials = array();
				if(!is_null($request->$serial)){
					foreach ($request->$serial as $key_serial => $value_serial) {
						array_push($item_serials, $value_serial);
					}
					
					$stDetails = [
						'item_code' => $value_item,
						'item_description' => $request->item_description[$key_item],
						'quantity' => $request->st_quantity[$key_item],
						'serial' => implode(",",(array)$item_serials),
						'wh_from' => $request->transfer_from,
						'wh_to' => $request->transfer_to,
						'stores_id' => CRUDBooster::myStore(),
						'memo' => $request->memo,
						'reason_id' => $request->reason,
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'transaction_type' => 'STW',
						'st_document_number' => $st_number,
						'sor_number' => (in_array(CRUDBooster::myChannel(),[1,4]) || (CRUDBooster::myChannel() == 2 && substr($value_item,0,1) == '7')) ? $st_number: NULL,
						'st_status_id' => 2,
						'has_serial' => 1,
						'status' => 'PENDING', //(is_null($request->hand_carrier)) ? 'FOR SCHEDULE' : 'FOR RECEIVING'
						'created_at' => date('Y-m-d H:i:s')
					];
					
				}
				else{
					$stDetails = [
						'item_code' => $value_item,
						'item_description' => $request->item_description[$key_item],
						'quantity' => $request->st_quantity[$key_item],
						'wh_from' => $request->transfer_from,
						'wh_to' => $request->transfer_to,
						'stores_id' => CRUDBooster::myStore(),
						'memo' => $request->memo,
						'reason_id' => $request->reason,
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'transaction_type' => 'STW',
						'st_document_number' => $st_number,
						'sor_number' => (in_array(CRUDBooster::myChannel(),[1,4]) || (CRUDBooster::myChannel() == 2 && substr($value_item,0,1) == '7')) ? $st_number: NULL,
						'st_status_id' => 2,
						'status' => 'PENDING', //(is_null($request->hand_carrier)) ? 'FOR SCHEDULE' : 'FOR RECEIVING'
						'created_at' => date('Y-m-d H:i:s')
					];
				}

				$pullout_id = DB::table('pullout')->insertGetId($stDetails);
				$record = true;
				$getSerial = self::getSerialById($pullout_id);

				if(!is_null($getSerial)){
					$serials = explode(",",$getSerial->serial);
					foreach ($serials as $serial) {
						if($serial != ""){
							DB::table('serials')->insert([
								'pullout_id' => $pullout_id, 
								'serial_number' => $serial,
								'created_at' => date('Y-m-d H:i:s')
							]);
						}
					}
				}
				
			}

			if($record && !empty($st_number))
                CRUDBooster::redirect(CRUDBooster::mainpath('print').'/'.$st_number,'','')->send();
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No pullout has been created','danger')->send();
            }
		}

		public function saveCreateMarketingSTW(Request $request)
		{
			$validator = \Validator::make($request->all(), [
				'transport_type' => 'required',
				'digits_code' => 'required',
				'transfer_from' => 'required',
				'transfer_to' => 'required',
				'reason' => 'required',
			],
			[
				'transport_type.required' => 'You have to choose transport by.',
				'digits_code.required' => 'You have to add pullout items.',
				'transfer_to.required' => 'You have to choose pullout to store/warehouse.',
				'reason.required' => 'You have to choose pullout reason.',
			]);
	
			if ($validator->fails()) {
				return redirect()->back()
				->withErrors($validator)
				->withInput();
			}

			$stDetails = array();
			$record = false;
			$code_counter = DB::table('code_counter')->where('id', 1)->value('pullout_refcode');
			$st_number = 'REF-'.str_pad($code_counter, 7, '0', STR_PAD_LEFT);

			foreach ($request->digits_code as $key_item => $value_item) {
				$serial = $value_item.'_serial_number';
				$item_serials = array();
				if(!is_null($request->$serial)){
					foreach ($request->$serial as $key_serial => $value_serial) {
						array_push($item_serials, $value_serial);
					}
					
					$stDetails = [
						'item_code' => $value_item,
						'item_description' => $request->item_description[$key_item],
						'quantity' => $request->st_quantity[$key_item],
						'serial' => implode(",",(array)$item_serials),
						'wh_from' => $request->transfer_from,
						'wh_to' => $request->transfer_to,
						'stores_id' => CRUDBooster::myStore(),
						'memo' => $request->memo,
						'reason_id' => $request->reason,
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'transaction_type' => 'STW Marketing',
						'st_document_number' => $st_number,
						'sor_number' => (in_array(CRUDBooster::myChannel(),[1,4]) || (CRUDBooster::myChannel() == 2 && substr($value_item,0,1) == '7')) ? $st_number: NULL,
						'st_status_id' => 2,
						'has_serial' => 1,
						'status' => (is_null($request->hand_carrier)) ? 'FOR SCHEDULE' : 'FOR RECEIVING',
						'created_at' => date('Y-m-d H:i:s')
					];
					
				}
				else{
					$stDetails = [
						'item_code' => $value_item,
						'item_description' => $request->item_description[$key_item],
						'quantity' => $request->st_quantity[$key_item],
						'wh_from' => $request->transfer_from,
						'wh_to' => $request->transfer_to,
						'stores_id' => CRUDBooster::myStore(),
						'memo' => $request->memo,
						'reason_id' => $request->reason,
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'transaction_type' => 'STW Marketing',
						'st_document_number' => $st_number,
						'sor_number' => (in_array(CRUDBooster::myChannel(),[1,4]) || (CRUDBooster::myChannel() == 2 && substr($value_item,0,1) == '7')) ? $st_number: NULL,
						'st_status_id' => 2,
						'status' => (is_null($request->hand_carrier)) ? 'FOR SCHEDULE' : 'FOR RECEIVING',
						'created_at' => date('Y-m-d H:i:s')
					];
				}

				$pullout_id = DB::table('pullout')->insertGetId($stDetails);
				$record = true;
				$getSerial = self::getSerialById($pullout_id);

				if(!is_null($getSerial)){
					$serials = explode(",",$getSerial->serial);
					foreach ($serials as $serial) {
						if($serial != ""){
							DB::table('serials')->insert([
								'pullout_id' => $pullout_id, 
								'serial_number' => $serial,
								'created_at' => date('Y-m-d H:i:s')
							]);
						}
					}
				}
				
			}

			DB::table('code_counter')->where('id', 1)->increment('pullout_refcode');
			
			if($record)
                CRUDBooster::redirect(CRUDBooster::mainpath('print').'/'.$st_number,'','')->send();
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No pullout has been created','danger')->send();
			}
			
		}

		public function saveCreateRMA(Request $request)
		{
			$validator = \Validator::make($request->all(), [
				'transport_type' => 'required',
				'digits_code' => 'required',
				'transfer_from' => 'required',
				'transfer_to' => 'required',
				'reason' => 'required',
			],
			[
				'transport_type.required' => 'You have to choose transport by.',
				'digits_code.required' => 'You have to add pullout items.',
				'transfer_to.required' => 'You have to choose pullout to store/warehouse.',
				'reason.required' => 'You have to choose pullout reason.',
			]);
	
			if ($validator->fails()) {
				return redirect()->back()
				->withErrors($validator)
				->withInput();
			}
			
			$posItemDetails = array();
			$stDetails = array();
			// $st_number = '';
			
			$store = DB::table('stores')->where('pos_warehouse',$request->transfer_from)->first();
			$customer = app(EBSPullController::class)->getPriceList($store->bea_so_store_name);
			
			// if(CRUDBooster::myPrivilegeName() != 'Marketing'){
				foreach ($request->digits_code as $key_item => $value_item) {
					$serial = $value_item.'_serial_number';
					
					if(!empty($request->$serial)){
						
						foreach ($request->$serial as $key_serial => $value_serial) {
							
							$posItemDetails[$value_item.'-'.$key_serial] = [
								'item_code' => $value_item,
								'quantity' => 1,
								'serial_number' => $value_serial,
								'item_price' => $request->price[$key_item]
							];
						}
						
					}
					else{
						
						$posItemDetails[$value_item.'-'.$key_item] = [
							'item_code' => $value_item,
							'quantity' => $request->st_quantity[$key_item],
							'item_price' => $request->price[$key_item]
						];
						
					}
				}
				//dd($posItemDetails);
				$postedST = app(POSPushController::class)->posCreateStockTransfer('BEAPOSMW', $request->transfer_branch, $request->transfer_from, $request->transfer_rma, $request->memo, $posItemDetails);
				$st_number = $postedST['data']['record']['fdocument_no'];
			// }

			$record=false;
			//save ST
			foreach ($request->digits_code as $key_item => $value_item) {
				$serial = $value_item.'_serial_number';
				$item_serials = array();
				if(!is_null($request->$serial)){
					foreach ($request->$serial as $key_serial => $value_serial) {
						array_push($item_serials, $value_serial);
					}
					
					$stDetails = [
						'item_code' => $value_item,
						'item_description' => $request->item_description[$key_item],
						'quantity' => $request->st_quantity[$key_item],
						'serial' => implode(",",(array)$item_serials),
						'wh_from' => $request->transfer_from,
						'wh_to' => $request->transfer_to,
						'stores_id' => CRUDBooster::myStore(),
						'memo' => $request->memo,
						'reason_id' => $request->reason,
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'transaction_type' => 'RMA',
						'st_document_number' => $st_number,
						'sor_number' => (in_array(CRUDBooster::myChannel(),[1,4])) ? $st_number: NULL,
						'st_status_id' => 2,
						'has_serial' => 1,
						'status' => 'PENDING', //(is_null($request->hand_carrier)) ? 'FOR SCHEDULE' : 'FOR RECEIVING'
						'created_at' => date('Y-m-d H:i:s')
					];
					
				}
				else{
					$stDetails = [
						'item_code' => $value_item,
						'item_description' => $request->item_description[$key_item],
						'quantity' => $request->st_quantity[$key_item],
						'wh_from' => $request->transfer_from,
						'wh_to' => $request->transfer_to,
						'stores_id' => CRUDBooster::myStore(),
						'memo' => $request->memo,
						'reason_id' => $request->reason,
						'transaction_type' => 'RMA',
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'st_document_number' => $st_number,
						'sor_number' => (in_array(CRUDBooster::myChannel(),[1,4])) ? $st_number: NULL,
						'st_status_id' => 2,
						'status' => 'PENDING', //(is_null($request->hand_carrier)) ? 'FOR SCHEDULE' : 'FOR RECEIVING'
						'created_at' => date('Y-m-d H:i:s')
					];
				}

				$pullout_id = DB::table('pullout')->insertGetId($stDetails);
				$record = true;
				$getSerial = self::getSerialById($pullout_id);

				if(!is_null($getSerial)){
					$serials = explode(",",$getSerial->serial);
					foreach ($serials as $serial) {
						if($serial != ""){
							DB::table('serials')->insert([
								'pullout_id' => $pullout_id, 
								'serial_number' => $serial,
								'created_at' => date('Y-m-d H:i:s')
							]);
						}
					}
				}
				
			}
			
			if($record)
                CRUDBooster::redirect(CRUDBooster::mainpath('print').'/'.$st_number,'','')->send();
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No pullout has been created','danger')->send();
            }
		}

		public function saveCreateMarketingRMA(Request $request)
		{
			# code...
		}

		public function saveSchedule(Request $request)
		{

			$record = DB::table('pullout')
				->where('st_document_number',$request->st_number)
				->update([
					'schedule_date' => $request->schedule_date,
					'status' => 'FOR RECEIVING'
				]);

			if($record)
                CRUDBooster::redirect(CRUDBooster::mainpath('print').'/'.$request->st_number,'','')->send();
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No transaction has been scheduled for transfer.','danger')->send();
			}
			
		}

		public function scanItemSearch(Request $request)
		{
			$data = array();
            $data['status_no'] = 0;
			$data['message'] ='No item found!';
			$qty = 1;
			
			$items = DB::table('items')
				->where('digits_code', $request->item_code)
				->first();

			$stockStatus = app(POSPullController::class)->getStockStatus($request->item_code, $request->warehouse);
			if($items->has_serial == 1){
				//$stockQty = (int) $stockStatus['data']['record']['0']['fqty']; //if serialize
				if(empty($stockStatus['data']['record'])){ //fix 2021-02-22
					$qty = -1;
				}
			}
			else{
				$stockQty = (int) $stockStatus['data']['record']['fqty'];
				if($request->quantity != 0){
					$qty = $stockQty - $request->quantity;
				}
			}

			if($items && $qty >= 0) {
				
				$data['status_no'] = 1;
				$data['message'] ='Item found!';
				$return_data['digits_code'] = $items->digits_code;
				$return_data['bea_item'] = $items->bea_item_id;
				$return_data['upc_code'] = $items->upc_code;
				$return_data['item_description'] = $items->item_description;
				$return_data['has_serial'] = $items->has_serial;
				$return_data['price'] = $items->store_cost;
				$data['items'] = $return_data;	
			}
			            
            echo json_encode($data);
            exit;
		}

		public function scanSerialSearch(Request $request)
		{
			$data = array();
            $data['status_no'] = 0;
			$data['message'] ='No serial found!';
			$serials = array();

			$qty = 1;
			
			$stockStatus = app(POSPullController::class)->getStockStatus($request->item_code, $request->warehouse);
			
			foreach ($stockStatus['data'] as $key => $value) {

				if($key == 'record'){
					
					if(count($value) == count($value, COUNT_RECURSIVE)){

						if(!in_array($value['flotno'], $serials)){
							array_push($serials, $value['flotno']);
							if($request->quantity != 0 && $request->serial_number == $value['flotno']){
								if(intval($value['fqty']) > 0)
									$qty = $value['fqty'] - $request->quantity;
								else
									$qty = -1;
							}	
						}
					}
					else{
						foreach ($value as $key_item => $value_item) {
							if(!in_array($value_item['flotno'], $serials)){
								array_push($serials, $value_item['flotno']);
								if($request->quantity != 0 && $request->serial_number == $value_item['flotno']){
									if(intval($value_item['fqty']) > 0)
										$qty = $value_item['fqty'] - $request->quantity;
									else
										$qty = -1;
								}	
							}
						}
					}
				}
			}

			if(in_array($request->serial_number, $serials) && $qty >= 0) {
				$data['status_no'] = 1;
				$data['message'] ='Serial found!';
				$data['serials'] = $request->serial_number;
			}
			            
            echo json_encode($data);
			exit;
		}

		public function voidStockTransfer($st_number)
		{
			$voidST = app(POSPushController::class)->voidStockTransfer($st_number);
			
			if($voidST['data']['record']['fresult'] == "ERROR"){
				$error = $voidST['data']['record']['errors']['error'];
				CRUDBooster::redirect(CRUDBooster::mainpath(),'Fail! '.$error,'warning')->send();
			}
			else{
				DB::table('pullout')->where('st_document_number',$st_number)->update([
					'status' => 'VOID',
					'updated_at' => date('Y-m-d H:i:s')
				]);
				CRUDBooster::redirect(CRUDBooster::mainpath(),'ST#'.$st_number.' has been voided successfully!','success')->send();
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

			// if(CRUDBooster::myChannel() == 1 && in_array(CRUDBooster::myPrivilegeName(), ["Requestor","Approver"])){
				$data['stDetails'] = DB::table('pullout')
					->join('reason', 'pullout.reason_id', '=', 'reason.bea_mo_reason')
					->join('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
					->where('st_document_number', $st_number)
					->select('pullout.*','reason.pullout_reason','reason.id as reason_id','transport_types.transport_type')
					->get();
			// }
			// elseif(CRUDBooster::myChannel() == 2 && in_array(CRUDBooster::myPrivilegeName(), ["Requestor","Approver","Logistics","Warehouse"])){
			// 	$data['stDetails'] = DB::table('pullout')
			// 		->join('reason', 'pullout.reason_id', '=', 'reason.bea_so_reason')
			// 		->join('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
			// 		->where('st_document_number', $st_number)
			// 		->select('pullout.*','reason.pullout_reason','reason.id as reason_id','transport_types.transport_type')
			// 		->get();
			// }
			if(count($data['stDetails']) == 0){
				$data['stDetails'] = DB::table('pullout')
					->join('reason', 'pullout.reason_id', '=', 'reason.bea_so_reason')
					->join('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
					->where('st_document_number', $st_number)
					->select('pullout.*','reason.pullout_reason','reason.id as reason_id','transport_types.transport_type')
					->get();
			}

			$data['transfer_from'] = DB::table('stores')->where('pos_warehouse',$data['stDetails'][0]->wh_from)->first();

			$data['transfer_to'] = DB::table('stores')->where('pos_warehouse',$data['stDetails'][0]->wh_to)->first();

			$items = DB::table('pullout')
			->where('st_document_number',$st_number)
			->select('id','item_code','quantity')
			->get();

			$data['stQuantity'] =  DB::table('pullout')
			->where('st_document_number', $st_number)
			->sum('quantity');

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

		public function getSchedule($st_number)
		{
			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'Schedule Pullout';

			// $data['stDetails'] = DB::table('pullout')->where('st_document_number', $st_number)->get();
			$data['stDetails'] = DB::table('pullout')
					->join('reason', 'pullout.reason_id', '=', 'reason.bea_mo_reason')
					->join('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
					->where('st_document_number', $st_number)
					->select('pullout.*','reason.pullout_reason','reason.id as reason_id','transport_types.transport_type')
					->get();

			if(count($data['stDetails']) == 0){
				$data['stDetails'] = DB::table('pullout')
					->join('reason', 'pullout.reason_id', '=', 'reason.bea_so_reason')
					->join('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
					->where('st_document_number', $st_number)
					->select('pullout.*','reason.pullout_reason','reason.id as reason_id','transport_types.transport_type')
					->get();
			}		

			$data['transfer_from'] = DB::table('stores')->where('pos_warehouse',$data['stDetails'][0]->wh_from)->first();

			$data['transfer_to'] = DB::table('stores')->where('pos_warehouse',$data['stDetails'][0]->wh_to)->first();

			$items = DB::table('pullout')
			->where('st_document_number',$st_number)
			->select('id','item_code','quantity')
			->get();

			$data['stQuantity'] =  DB::table('pullout')
			->where('st_document_number', $st_number)
			->sum('quantity');

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
			
			$this->cbView("pullout.schedule", $data);
		}

		public function getPrint($st_number)
		{
			if(!CRUDBooster::isUpdate() && $this->global_privilege == false) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'Pullout Details';

			$data['stDetails'] = DB::table('pullout')->where('st_document_number', $st_number)->get();

			$data['transfer_from'] = DB::table('stores')->where('pos_warehouse',$data['stDetails'][0]->wh_from)->first();

			$data['transfer_to'] = DB::table('stores')->where('pos_warehouse',$data['stDetails'][0]->wh_to)->first();

			$items = DB::table('pullout')
			->where('st_document_number',$st_number)
			->select('id','item_code','quantity')
			->get();

			$data['stQuantity'] =  DB::table('pullout')
			->where('st_document_number', $st_number)
			->sum('quantity');

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

			$this->cbView("pullout.print", $data);
		}

		public function getSerialById($pullout_id)
		{
			return DB::table('pullout')->where('id',$pullout_id)
            	->select('serial')->first();
		}

		public function updateSORNumber()
		{
			$pullouts = DB::table('pullout')->where('status','FOR SCHEDULE')->orWhere('status','FOR RECEIVING')->whereNull('sor_number')->get();
			$record = false;
			foreach ($pullouts as $key => $value) {
				$sor_number = app(EBSPullController::class)->getOrderNumber($value->st_document_number);

				if(!empty($sor_number)){
					DB::table('pullout')->where('st_document_number', $value->st_document_number)->update([
						'sor_number' => $sor_number->order_number,
					]);
					$record = true;
				}
			}

			// if($record){
			// 	CRUDBooster::redirect(CRUDBooster::mainpath(),'Pullout SOR number has been created!','success')->send();
			// }
			// else{
			// 	CRUDBooster::redirect(CRUDBooster::mainpath(),'No pullout created in BEA!','info')->send();
			// }
		}
	}
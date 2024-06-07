<?php namespace App\Http\Controllers;

	use Session;
	use DB;
	use CRUDBooster;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\File;
	use Illuminate\Http\Request;
	use App\ApprovalMatrix;
	use Carbon\Carbon;
	use App\CodeCounter;
	use App\PosPullHeader;
	use App\PosPullLines;

	class AdminStoreTransferController extends \crocodicstudio\crudbooster\controllers\CBController {
		private const Pending = 'PENDING';
		private const Void    = 'VOID';
		private const ForSchedule  = 'FOR SCHEDULE';
		private const ForReceiving = 'FOR RECEIVING';
		private const Confirmed = 'CONFIRMED';
	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "st_document_number";
			$this->limit = "20";
			$this->orderby = "created_date,desc,status,asc";
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
			$this->table = "pos_pull_headers";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"ST #","name"=>"st_document_number"];
			$this->col[] = ["label"=>"Received ST #","name"=>"received_st_number"];
			$this->col[] = ["label"=>"From WH","name"=>"stores_id","join"=>"stores,pos_warehouse_name"];
			$this->col[] = ["label"=>"To WH","name"=>"stores_id_destination","join"=>"stores,pos_warehouse_name"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Transport Type","name"=>"transport_types_id","join"=>"transport_types,transport_type"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_date"];
			
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
			if(!in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL","Warehouse","RMA"])){
				$this->addaction[] = [
					'title'=>'Void ST',
					'url'=>CRUDBooster::mainpath('void-st').'/[st_document_number]',
					'icon'=>'fa fa-times',
					'color'=>'danger',
					'showIf'=>"[status]==".self::Pending."",
					'confirmation'=>'yes',
					'confirmation_title'=>'Confirm Voiding',
					'confirmation_text'=>'Are you sure to VOID this transaction?'
				];
			}
			//$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('print').'/[st_document_number]','icon'=>'fa fa-print','color'=>'info','showIf'=>"[status]=='FOR SCHEDULE'"];
			if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName() ,["LOG TM","LOG TL"])){
				$this->addaction[] = ['title'=>'Schedule','url'=>CRUDBooster::mainpath('schedule').'/[st_document_number]','icon'=>'fa fa-calendar','color'=>'warning','showIf'=>"[status]=='FOR SCHEDULE'"];
			}
			
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
			if(CRUDBooster::getCurrentMethod() == 'getIndex' && !in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL","Approver","Gashapon Requestor"])){
				$this->index_button[] = ['label'=>'Create STS','url'=>route('st.scanning'),'icon'=>'fa fa-plus','color'=>'success'];
			}elseif(CRUDBooster::getCurrentMethod() == 'getIndex' && in_array(CRUDBooster::myPrivilegeId(),[27])){
				$this->index_button[] = ['label'=>'Create STS','url'=>route('st.gis.scanning'),'icon'=>'fa fa-plus','color'=>'success'];
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
				
				if (in_array(CRUDBooster::myPrivilegeName() ,["LOG TM","LOG TL"])) {
					$query->select('pos_pull_headers.st_document_number','pos_pull_headers.wh_from','pos_pull_headers.wh_to','pos_pull_headers.status')
					->where('pos_pull_headers.status','FOR SCHEDULE')
					->where('transport_types_id',1)
					->distinct();
				}
				
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Approver","Franchise Approver"])){
					//get approval matrix
					$approvalMatrix = ApprovalMatrix::where('approval_matrix.cms_users_id', CRUDBooster::myId())->get();
					
					$approval_array = array();
					foreach($approvalMatrix as $matrix){
						array_push($approval_array, $matrix->store_list);
					}
					$approval_string = implode(",",$approval_array);
					$storeList = array_map('intval',explode(",",$approval_string));
	
					$query->whereIn('pos_pull_headers.stores_id', array_values((array)$storeList))
						->select('pos_pull_headers.st_document_number','pos_pull_headers.wh_from','pos_pull_headers.wh_to','pos_pull_headers.status','pos_pull_headers.created_date');
				} 
				//Cashier, Customer Service, Associate, Requestor
				else if (in_array(CRUDBooster::myPrivilegeId(),  [30,32,2])){
					$query->select('pos_pull_headers.st_document_number','pos_pull_headers.wh_from','pos_pull_headers.wh_to','pos_pull_headers.status','pos_pull_headers.created_date')
					->where('pos_pull_headers.stores_id',$store->id)
					->where('pos_pull_headers.created_by', CRUDBooster::myId()) 
					->orderByRaw('FIELD(pos_pull_headers.status, "PENDING", "FOR SCHEDULE","FOR RECEIVING", "RECEIVED", "VOID")')
					->distinct();

				}
				//Store Head
				else if (CRUDBooster::myPrivilegeId() == 29){
					$query->select('pos_pull_headers.st_document_number','pos_pull_headers.wh_from','pos_pull_headers.wh_to','pos_pull_headers.status','pos_pull_headers.created_date')
					->where('pos_pull_headers.stores_id',$store->id)
					->orderByRaw('FIELD(pos_pull_headers.status, "PENDING", "FOR SCHEDULE","FOR RECEIVING", "RECEIVED", "VOID")')
					->distinct();
				
				}
				//Area Manager, Operations Manager
				else if (in_array(CRUDBooster::myPrivilegeId(), [31,28])){

					$query->select('pos_pull_headers.st_document_number','pos_pull_headers.wh_from','pos_pull_headers.wh_to','pos_pull_headers.status','pos_pull_headers.created_date')
					->orderByRaw('FIELD(pos_pull_headers.status, "PENDING", "FOR SCHEDULE","FOR RECEIVING", "RECEIVED", "VOID")')
					->distinct();
		
				}
				//Others
				else{
					$query->select('pos_pull_headers.st_document_number','pos_pull_headers.wh_from','pos_pull_headers.wh_to','pos_pull_headers.status','pos_pull_headers.created_date')
					->where('pos_pull_headers.stores_id',$store->id)
					->orderByRaw('FIELD(pos_pull_headers.status, "PENDING", "FOR SCHEDULE","FOR RECEIVING", "RECEIVED", "VOID")')
					->distinct();
				}
				
			}

			else{
				$query->select('pos_pull_headers.st_document_number','pos_pull_headers.wh_from','pos_pull_headers.wh_to','pos_pull_headers.status','pos_pull_headers.created_date');
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
			if($column_index == 5){
				if($column_value == self::Pending){
					$column_value = '<span class="label label-warning">'.self::Pending.'</span>';
				}
				else if($column_value == self::Confirmed){
					$column_value = '<span class="label label-warning">'.self::Confirmed.'</span>';
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
				else if($column_value == "CLOSED"){
					$column_value = '<span class="label label-danger">CLOSED</span>';
				}
			}
			if($column_index == 6){
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

		public function getScan(){
			$this->cbLoader();
			$data = array();
			$data['page_title'] = 'Create STS';
			if(CRUDBooster::isSuperadmin()){
				$data['transfer_from'] = DB::table('stores')
				->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name')
				->where('status', 'ACTIVE')
				->orderBy('pos_warehouse_name', 'ASC')
				->get();
			}else{
				$data['transfer_from'] = DB::table('stores')
				->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name','sts_group')
				->whereIn('id',CRUDBooster::myStore())
				->where('status', 'ACTIVE')
				->orderBy('pos_warehouse_name', 'ASC')
				->get();
			}

			$data['reasons'] = DB::table('reason')
				->select('id','pullout_reason')
				->where('transaction_type_id',4) //STS
				->where('status','ACTIVE')
				->get();
			
			$data['transport_types'] = DB::table('transport_types')
				->select('id','transport_type')
				->where('status','ACTIVE')
				->get();
            
			if(CRUDBooster::myChannel() == 2){
				$data['transfer_to'] = DB::table('stores')
					->select('id','pos_warehouse','pos_warehouse_name')
					->whereNotIn('id',CRUDBooster::myStore())
					->where('status','ACTIVE')
					->where(function($query) use($data) {
						$query->where('sts_group',$data['transfer_from'][0]->sts_group)
						->orWhereIn('id',[62,63]);
					})
					->orderBy('pos_warehouse_name', 'ASC')
					->get();
			}
			elseif (in_array(CRUDBooster::myStore()[0], [62,63])) {
				$data['transfer_to'] = DB::table('stores')
					->select('id','pos_warehouse','pos_warehouse_name')
					->where('status','ACTIVE')
					->whereNotIn('id',CRUDBooster::myStore())
					->where(function($query){
						$query->orWhereIn('channel_id',[1,2]);
					})
					->orderBy('pos_warehouse_name', 'ASC')
					->get();
			}
			else{
				$data['transfer_to'] = DB::table('stores')
					->select('id','pos_warehouse','pos_warehouse_name')
					->whereNotIn('id',CRUDBooster::myStore())
					->where('sts_group',$data['transfer_from'][0]->sts_group)
					->where('status','ACTIVE')
					->orderBy('pos_warehouse_name', 'ASC')
					->get();
			}
				
			if(substr($data['transfer_from'][0]->pos_warehouse_name, -3) == 'FBD'){
				$this->cbView("stock-transfer.scan-online-fbd", $data);
			}
			else{
				$this->cbView("stock-transfer.scan", $data);
			}
			
		}

		public function scanItemSearch(Request $request){
			$data = array();
			$item_serials = array();
            $data['status_no'] = 0;
			$data['message'] ='No item found!';
			$qty = 1;
			
			$items = DB::table('items')
				->where('digits_code', $request->item_code)
				->first();

			$stockStatus = app(POSPullController::class)->getStockStatus($request->item_code, $request->warehouse);
			
			if($items->has_serial == 1){
				//$stockQty = (int) $stockStatus['data']['record']['0']['fqty']; //if serialize
				$stock_status = array();
				
				if(count((array)$stockStatus['data']['record']) == 6 && empty($stockStatus['data']['record'][1])){
				    $stock_status = $stockStatus['data'];
				}
				else{
				    $stock_status = $stockStatus['data']['record'];
				}
				if(empty($stockStatus['data']['record'])){ //fix 2021-02-22
					$qty = -1;
				}else{
					foreach ($stock_status as $value) {
						if(is_array($value)){
							if($value['fqtyz'] != 0.000000 ){
								array_push($item_serials, $value['flotno']);
							}
						}else{
							if($value->fqtyz != 0.000000 ){
								array_push($item_serials, $value->flotno);
							}
						}
					}
				}
				
				if(empty($item_serials)){
                   $qty = -1; 
                }
			}
			else{
				$stockQty = ((int) $stockStatus['data']['record'][0]['fqtyz']) ? (int) $stockStatus['data']['record'][0]['fqtyz'] : (int) $stockStatus['data']['record']['fqtyz'];
				if(empty($stockQty)){
				    $stockQty = ((int) $stockStatus['data']['record'][0]->fqtyz) ? (int) $stockStatus['data']['record'][0]->fqtyz : (int) $stockStatus['data']['record']->fqtyz;
				
				}
				if($request->quantity != 0){
					$qty = $stockQty - $request->quantity;
				}
			}

			if($items && $qty >= 0) {
				
				$data['status_no'] = 1;
				$data['message'] ='Item found!';
				$return_data['digits_code'] = $items->digits_code;
				$return_data['upc_code'] = $items->upc_code;
				$return_data['item_description'] = $items->item_description;
				$return_data['has_serial'] = $items->has_serial;
				$return_data['item_serial'] = $item_serials;
				$return_data['price'] = $items->store_cost;
				$data['items'] = $return_data;	
			}
			            
            echo json_encode($data);
            exit;
		}

		public function scanSerialSearch(Request $request){
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
								if(intval($value['fqtyz']) > 0)
									$qty = $value['fqtyz'] - $request->quantity;
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
									if(intval($value_item['fqtyz']) > 0)
										$qty = $value_item['fqtyz'] - $request->quantity;
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

		public function saveCreateST(Request $request)
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
				'digits_code.required' => 'You have to add stock transfer items.',
				'transfer_to.required' => 'You have to choose transfer to store.',
				'transfer_from.required' => 'You have to choose transfer from store.',
				'reason.required' => 'You have to choose transfer reason.',
			]);
	
			if ($validator->fails()) {
				return redirect()->back()
				->withErrors($validator)
				->withInput();
			}

			$posItemDetails = array();
			$stDetails = array();

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

			$record=false;

			//save ST
			//CREATE HEADER
			$code_counter = CodeCounter::where('id', 3)->value('pullout_refcode');
			$st_ref_no = 'MWSTS-'.str_pad($code_counter, 7, '0', STR_PAD_LEFT);
			$headerDetails = [
				'reference_number'      => $st_ref_no,
				'status'                => self::Pending,
				'wh_from'               => $request->transfer_from,
				'wh_to'                 => $request->transfer_to,
				'stores_id'             => (int)(implode("",CRUDBooster::myStore())),
				'channel_id'            => CRUDBooster::myChannel(),
				'stores_id_destination' => $request->stores_id_destination,
				'memo'                  => $request->memo,
				'reason_id'             => $request->reason,
				'transport_types_id'    => $request->transport_type,
				'hand_carrier'          => $request->hand_carrier,
				'st_document_number'    => $st_number,
				'st_status_id'          => 2,
				'created_date'          => date('Y-m-d'),
				'created_by'            => CRUDBooster::myId(),
				'created_at'            => date('Y-m-d H:i:s')
			];

			$pos_pull_id = DB::table('pos_pull_headers')->insertGetId($headerDetails);
			DB::table('code_counter')->where('id', 3)->increment('pullout_refcode');
			//Save lines
			foreach ($request->digits_code as $key_item => $value_item) {
				$serial = $value_item.'_serial_number';
				$item_serials = array();
				if(!is_null($request->$serial)){
					foreach ($request->$serial as $key_serial => $value_serial) {
						array_push($item_serials, $value_serial);
					}
					
					$stDetails = [
						'pos_pull_header_id' => $pos_pull_id,
						'item_code'          => $value_item,
						'item_description'   => $request->item_description[$key_item],
						'quantity'           => $request->st_quantity[$key_item],
						'serial'             => implode(",",(array)$item_serials),
						'has_serial'         => 1,
						'created_at'         => date('Y-m-d H:i:s')
					];
					
				}
				else{
					$stDetails = [
						'pos_pull_header_id' => $pos_pull_id,
						'item_code'          => $value_item,
						'item_description'   => $request->item_description[$key_item],
						'quantity'           => $request->st_quantity[$key_item],
						'created_at'         => date('Y-m-d H:i:s')
					];
				}

				$ebsPullId = DB::table('pos_pull')->insertGetId($stDetails);
				$record = true;
				$getSerial = self::getSerialById($ebsPullId);

				if(!is_null($getSerial)){
					$serials = explode(",",$getSerial->serial);
					foreach ($serials as $serial) {
						if($serial != ""){
							DB::table('serials')->insert([
								'pos_pull_id' => $ebsPullId, 
								'serial_number' => $serial,
								'created_at' => date('Y-m-d H:i:s')
							]);
						}
					}
				}
				
			}

			//SAVE TO POS
			$refcode = DB::table('pos_pull_headers')->where('id',$pos_pull_id)->first();
			$checkIfExistInPos = self::checkPOSStockTransfer($refcode->reference_number);
			if($checkIfExistInPos != 'POSTED'){
				$transfer_dest = (empty($request->transfer_rma)) ? $request->transfer_transit : $request->transfer_rma;
				$postedST = app(POSPushController::class)->posCreateStockTransfer(
				$refcode->reference_number, 
				$request->transfer_branch, 
				$request->transfer_from, 
				$transfer_dest, 
				$request->memo, 
				$posItemDetails);
				
				// 	$postedST = app(POSPushController::class)->posCreateStockTransfer($refcode, $request->transfer_branch, $request->transfer_from, $request->transfer_transit, $request->memo, $posItemDetails);
				
				\Log::info('sts create ST: '.json_encode($postedST));
				//20210215 add checking if st number is not null
				$st_number = $postedST['data']['record']['fdocument_no'];
				//UPDATE ST NUMBER IN POS HEADERS TABLE
				DB::table('pos_pull_headers')->where('id',$pos_pull_id)->update(['st_document_number'=>$st_number]);
			}
			
			if($record && !empty($st_number)){
			    CRUDBooster::insertLog(trans("crudbooster.sts_created", ['ref_number' =>$st_number]));
				CRUDBooster::redirect(CRUDBooster::mainpath('print').'/'.$st_number,'','')->send();
                //CRUDBooster::redirect(CRUDBooster::mainpath('print').'/'.$st_number,'Stock transfer created successfully!','success')->send();
			}
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No stock transfer has been created','danger')->send();
            }

		}

		public function saveCreateOnlineSTBackup(Request $request)
		{
			$validator = \Validator::make($request->all(), [
				
				'digits_code' => 'required',
				'transfer_from' => 'required',
				'transfer_to' => 'required',
				'reason' => 'required',
			],
			[
				'digits_code.required' => 'You have to add stock transfer items.',
				'transfer_from.required' => 'You have to choose transfer from store.',
				'transfer_to.required' => 'You have to choose transfer to store.',
				'reason.required' => 'You have to choose transfer reason.',
			]);
	
			if ($validator->fails()) {
				return redirect()->back()
				->withErrors($validator)
				->withInput();
			}

			$posItemDetails = array();
			$stDetails = array();

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
			$refcode = 'BEAPOSMW-STS-'.date('His');
			$postedST = app(POSPushController::class)->posCreateStockTransfer($refcode, $request->transfer_branch, $request->transfer_from, $request->transfer_transit, $request->memo, $posItemDetails);
			
			\Log::info('sts create ST: '.json_encode($postedST));
			//20210215 add checking if st number is not null
			$st_number = $postedST['data']['record']['fdocument_no'];
			$record=false;
			if($st_number == ''){
				//call old forms with error message
			}
			
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
						'stores_id' => $request->stores_id,
						'channel_id' => CRUDBooster::myChannel(),
						'stores_id_destination' => $request->stores_id_destination,
						'memo' => $request->memo,
						'transfer_date' => $request->transfer_date,
						'reason_id' => $request->reason,
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'st_document_number' => $st_number,
						'st_status_id' => 2,
						'has_serial' => 1,
						'status' => 'PENDING',
						'created_date' => date('Y-m-d'),
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
						'stores_id' => $request->stores_id,
						'channel_id' => CRUDBooster::myChannel(),
						'stores_id_destination' => $request->stores_id_destination,
						'memo' => $request->memo,
						'transfer_date' => $request->transfer_date,
						'reason_id' => $request->reason,
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'st_document_number' => $st_number,
						'st_status_id' => 2,
						'status' => 'PENDING',
						'created_date' => date('Y-m-d'),
						'created_at' => date('Y-m-d H:i:s')
					];
				}

				$pos_pull_id = DB::table('pos_pull')->insertGetId($stDetails);
				$record = true;
				$getSerial = self::getSerialById($pos_pull_id);

				if(!is_null($getSerial)){
					$serials = explode(",",$getSerial->serial);
					foreach ($serials as $serial) {
						if($serial != ""){
							DB::table('serials')->insert([
								'pos_pull_id' => $pos_pull_id, 
								'serial_number' => $serial,
								'created_at' => date('Y-m-d H:i:s')
							]);
						}
					}
				}
				
			}
			
			if($record && !empty($st_number))
				CRUDBooster::redirect(CRUDBooster::mainpath(),'Stock transfer created successfully!','success')->send();
                //CRUDBooster::redirect(CRUDBooster::mainpath('print').'/'.$st_number,'Stock transfer created successfully!','success')->send();
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No stock transfer has been created','danger')->send();
            }

		}
		
		public function saveCreateOnlineST(Request $request)
		{
			$validator = \Validator::make($request->all(), [
				
				'digits_code' => 'required',
				'transfer_from' => 'required',
				'transfer_to' => 'required',
				'reason' => 'required',
			],
			[
				'digits_code.required' => 'You have to add stock transfer items.',
				'transfer_from.required' => 'You have to choose transfer from store.',
				'transfer_to.required' => 'You have to choose transfer to store.',
				'reason.required' => 'You have to choose transfer reason.',
			]);
	
			if ($validator->fails()) {
				return redirect()->back()
				->withErrors($validator)
				->withInput();
			}

			$posItemDetails = array();
			$stDetails = array();

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
			$refcode = 'BEAPOSMW-STS-'.date('His');
			$postedST = app(POSPushController::class)->posCreateStockTransfer($refcode, $request->transfer_branch, $request->transfer_from, $request->transfer_transit, $request->memo, $posItemDetails);
			
			\Log::info('sts create ST: '.json_encode($postedST));
			//20210215 add checking if st number is not null
			$st_number = $postedST['data']['record']['fdocument_no'];
			$record=false;
			if(empty($st_number)){
                //back to old form
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No stock transfer has been created. '.$postedST['data']['record']['errors']['error'] ,'danger')->send();
            }
			
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
						'stores_id' => $request->stores_id,
						'channel_id' => CRUDBooster::myChannel(),
						'stores_id_destination' => $request->stores_id_destination,
						'memo' => $request->memo,
						'transfer_date' => $request->transfer_date,
						'reason_id' => $request->reason,
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'st_document_number' => $st_number,
						'st_status_id' => 2,
						'has_serial' => 1,
						'status' => 'PENDING',
						'created_date' => date('Y-m-d'),
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
						'stores_id' => $request->stores_id,
						'channel_id' => CRUDBooster::myChannel(),
						'stores_id_destination' => $request->stores_id_destination,
						'memo' => $request->memo,
						'transfer_date' => $request->transfer_date,
						'reason_id' => $request->reason,
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'st_document_number' => $st_number,
						'st_status_id' => 2,
						'status' => 'PENDING',
						'created_date' => date('Y-m-d'),
						'created_at' => date('Y-m-d H:i:s')
					];
				}

				$pos_pull_id = DB::table('pos_pull')->insertGetId($stDetails);
				$record = true;
				$getSerial = self::getSerialById($pos_pull_id);

				if(!is_null($getSerial)){
					$serials = explode(",",$getSerial->serial);
					foreach ($serials as $serial) {
						if($serial != ""){
							DB::table('serials')->insert([
								'pos_pull_id' => $pos_pull_id, 
								'serial_number' => $serial,
								'created_at' => date('Y-m-d H:i:s')
							]);
						}
					}
				}
				
			}
			
			if($record && !empty($st_number)){
			    CRUDBooster::insertLog(trans("crudbooster.sts_created", ['ref_number' =>$st_number]));
				CRUDBooster::redirect(CRUDBooster::mainpath(),'Stock transfer created successfully!','success')->send();
			}
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No stock transfer has been created','danger')->send();
            }

		}

		public function saveSchedule(Request $request)
		{

			$record = DB::table('pos_pull_headers')
				->where('st_document_number',$request->st_number)
				->update([
					'scheduled_at' => $request->schedule_date,
					'scheduled_by' => CRUDBooster::myId(),
					'status' => 'FOR RECEIVING'
				]);

			if($record)
                CRUDBooster::redirect(CRUDBooster::mainpath('print').'/'.$request->st_number,'','')->send();
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No transaction has been scheduled for transfer.','danger')->send();
            }
		}

		public function getSerialById($pos_pull_id)
		{
			return DB::table('pos_pull')->where('id',$pos_pull_id)
            	->select('serial')->first();
		}

		public function getPrint($st_number)
		{
			$this->cbLoader();
			$data = array();
			$data['page_title'] = 'STS Details';
			$data['stDetails'] = PosPullHeader::getDetails($st_number)->first();
			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails']->stores_id)->first();
			$data['transfer_to'] = DB::table('stores')->where('id',$data['stDetails']->stores_id_destination)->first();
			$items = PosPullLines::getItems($data['stDetails']->id)->get();
			$data['stQuantity'] =  PosPullLines::getStQuantity($data['stDetails']->id);

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
					'item_description' => $item_detail != NULL ? $item_detail->item_description : $value->item_description,
					'price' => $item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;

			$this->cbView("stock-transfer.print", $data);
		}

		public function voidStockTransfer($st_number)
		{
			$isGisSt = DB::table('pos_pull_headers')->where('st_document_number',$st_number)->first();
			if(!$isGisSt->location_id_from){
				$voidST = app(POSPushController::class)->voidStockTransfer($st_number);
				
				if($voidST['data']['record']['fresult'] == "ERROR"){
					$error = $voidST['data']['record']['errors']['error'];
					CRUDBooster::redirect(CRUDBooster::mainpath(),'Fail! '.$error,'warning')->send();
				}
				else{
					DB::table('pos_pull_headers')->where('st_document_number',$st_number)->update([
						'status' => 'VOID',
						'updated_at' => date('Y-m-d H:i:s')
					]);
					CRUDBooster::redirect(CRUDBooster::mainpath(),'ST#'.$st_number.' has been voided successfully!','success')->send();
				}
			}else{
				$items = DB::table('pos_pull')->where('pos_pull_header_id',$isGisSt->id)->get();
				$from_intransit_gis_sub_location = DB::connection('gis')->table('sub_locations')->where('status','ACTIVE')
				->where('location_id',$isGisSt->location_id_from)->where('description','LIKE', '%'.'IN TRANSIT'.'%')->first();
				//UPDATE STATUS HEADER
				DB::table('pos_pull_headers')->where('st_document_number',$st_number)->update([
					'status' => 'VOID',
					'updated_at' => date('Y-m-d H:i:s')
				]);
				//ADD QTY IN GIS INVENTORY LINES TO FROM LOCATION
				foreach($items as $key => $item){
					//REVERT TO INVETORY CAPSULE
					DB::connection('gis')->table('inventory_capsules')
					->leftjoin('inventory_capsule_lines','inventory_capsules.id','inventory_capsule_lines.inventory_capsules_id')
					->leftjoin('items','inventory_capsules.item_code','items.digits_code2')
					->where([
						'items.digits_code' => $item->item_code,
						'inventory_capsules.locations_id' => $isGisSt->location_id_from
					])
					->where('inventory_capsule_lines.sub_locations_id',$isGisSt->sub_location_id_from)
					->update([
						'qty' => DB::raw("qty + $item->quantity"),
						'inventory_capsule_lines.updated_at' => date('Y-m-d H:i:s')
					]);

					//REMOVE IN INTRANSIT
					DB::connection('gis')->table('inventory_capsules')
					->leftjoin('inventory_capsule_lines','inventory_capsules.id','inventory_capsule_lines.inventory_capsules_id')
					->leftjoin('items','inventory_capsules.item_code','items.digits_code2')
					->where([
						'items.digits_code' => $item->item_code,
						'inventory_capsules.locations_id' => $isGisSt->location_id_from
					])
					->where('inventory_capsule_lines.sub_locations_id',$from_intransit_gis_sub_location->id)
					->update([
						'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty - $item->quantity"),
						'inventory_capsule_lines.updated_at' => date('Y-m-d H:i:s')
					]);

					//ADD GIS MOVEMENT HISTORY
					//get item code
					$gis_mw_name = DB::connection('gis')->table('cms_users')->where('email','mw@gashapon.ph')->first();
					$item_code = DB::connection('gis')->table('items')->where('digits_code',$item->item_code)->first();
					$capsuleAction = DB::connection('gis')->table('capsule_action_types')->where('status','ACTIVE')
					->where('description','ST-REVERSAL')->first();
					DB::connection('gis')->table('history_capsules')->insert([
						'reference_number' => $st_number,
						'item_code' => $item_code->digits_code2,
						'capsule_action_types_id' => $capsuleAction->id,
						'locations_id' => $isGisSt->location_id_from,
						'from_sub_locations_id' => $isGisSt->sub_location_id_from,
						'to_sub_locations_id' => $from_intransit_gis_sub_location->id,
						'qty' => $item->quantity,
						'created_at' => date('Y-m-d H:i:s'),
						'created_by' => $gis_mw_name->id
					]);
					DB::connection('gis')->table('history_capsules')->insert([
						'reference_number' => $st_number,
						'item_code' => $item_code->digits_code2,
						'capsule_action_types_id' => $capsuleAction->id,
						'locations_id' => $isGisSt->location_id_from,
						'from_sub_locations_id' => $from_intransit_gis_sub_location->id,
						'to_sub_locations_id' => $isGisSt->sub_location_id_from,
						'qty' => -1 * abs($item->quantity),
						'created_at' => date('Y-m-d H:i:s'),
						'created_by' => $gis_mw_name->id
					]);
				}
				CRUDBooster::redirect(CRUDBooster::mainpath(), $st_number.' has been reverse successfully!','success')->send();
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
			$data['stDetails'] = PosPullHeader::getDetails($st_number)->first();
			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails']->stores_id)->first();
			$data['transfer_to'] = DB::table('stores')->where('id',$data['stDetails']->stores_id_destination)->first();
			$items = PosPullLines::getItems($data['stDetails']->id)->get();
			$data['stQuantity'] =  PosPullLines::getStQuantity($data['stDetails']->id);
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
					'item_description' => $item_detail != NULL ? $item_detail->item_description : $value->item_description,
					'price' => $item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;
			$this->cbView("stock-transfer.detail", $data);
		}

		public function getSchedule($st_number)
		{
			if(!CRUDBooster::isUpdate() && $this->global_privilege == false || $this->button_detail == false) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$data['page_title'] = 'Schedule Stock Transfer';
			$data['stDetails'] = PosPullHeader::getDetails($st_number)->first();
			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails']->stores_id)->first();
			$data['transfer_to'] = DB::table('stores')->where('id',$data['stDetails']->stores_id_destination)->first();
			$items = PosPullLines::getItems($data['stDetails']->id)->get();
			$data['stQuantity'] =  PosPullLines::getStQuantity($data['stDetails']->id);

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
					'item_description' => $item_detail != NULL ? $item_detail->item_description : $value->item_description,
					'price' => $item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;
			$this->cbView("stock-transfer.schedule", $data);
		}

		public function autoRejectHandCarry(){
			// Calculate the date and time 24 hours ago
			$twentyFourHoursAgo = Carbon::now()->subDay();
			$getAutoReject = DB::table('pos_pull')->whereNotNull('approved_at')
			->where('transport_types_id',2)
			->where('approved_at', '<=', $twentyFourHoursAgo)
			->whereNotIn('status',['VOID','RECEIVED'])
			->get();
			dd($getAutoReject);

			// $voidST = app(POSPushController::class)->voidStockTransfer($st_number);
			if($voidST['data']['record']['fresult'] == "ERROR"){
				$error = $voidST['data']['record']['errors']['error'];
				CRUDBooster::redirect(CRUDBooster::mainpath(),'Fail! '.$error,'warning')->send();
			}
			else{
				DB::table('pos_pull')->where('st_document_number',$st_number)->update([
					'status' => 'VOID',
					'updated_at' => date('Y-m-d H:i:s')
				]);
				CRUDBooster::redirect(CRUDBooster::mainpath(),'ST#'.$st_number.' has been voided successfully!','success')->send();
			}
		}

		public function checkPOSStockTransfer($refcode){
			$data = [];
			$stockTransfers = (new POSPullController)->getStockTransferByRef($refcode);
			$data['message'] = $stockTransfers['data']['record']['fstatus_flag'];
			return $data;
		}

	}
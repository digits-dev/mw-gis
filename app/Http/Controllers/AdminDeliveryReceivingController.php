<?php namespace App\Http\Controllers;

use App\EBSPull;
use App\StoreName;
use Session;
	use DB;
	use CRUDBooster;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\File;
	use Illuminate\Http\Request;

	class AdminDeliveryReceivingController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct()
		{
			// Apply the middleware to a specific method
			$this->middleware('check_access_time')->only('getReceiving');
		}

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "order_number";
			$this->limit = "20";
			$this->orderby = "data_pull_date,asc,dr_number";
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
			$this->table = "ebs_pull";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"DR #","name"=>"dr_number"];
			$this->col[] = ["label"=>"Customer Name","name"=>"customer_name"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Created Date","name"=>"data_pull_date"];
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
			
			$this->addaction[] = ['title'=>'Receive DR','url'=>CRUDBooster::mainpath().'/[dr_number]','icon'=>'fa fa-cube','color'=>'info','showIf'=>"[status]=='PENDING'"];
			$this->addaction[] = ['title'=>'Details','url'=>CRUDBooster::mainpath().'/details/[dr_number]?return_url='.urlencode(\Request::fullUrl()),'icon'=>'fa fa-eye','color'=>'primary'];

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
			if(!CRUDBooster::isSuperadmin()){
				$stores = DB::table('stores')->whereIn('id', CRUDBooster::myStore())->get();
				$storeAccess = array();
				foreach($stores as $store ){
				    array_push($storeAccess, $store->bea_mo_store_name);
				    array_push($storeAccess, $store->bea_so_store_name);
				}
				$query->select('ebs_pull.dr_number','ebs_pull.customer_name','ebs_pull.status')
					->where('ebs_pull.status','PENDING')
					->whereIn('ebs_pull.customer_name', $storeAccess)
					->distinct();   
			}
			else{
				$query->select('ebs_pull.dr_number','ebs_pull.customer_name','ebs_pull.status')
					->where('ebs_pull.status','PENDING')
					->orderBy('ebs_pull.customer_name','ASC')
					->distinct();
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
				else if($column_value == "RECEIVED"){
					$column_value = '<span class="label label-success">RECEIVED</span>';
				}
				else if($column_value == "VOID"){
					$column_value = '<span class="label label-danger">VOID</span>';
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
		
		public function getReceiving($dr_number)
		{
		    
			$this->cbLoader();

			$data = array();
			$item_data = array();
			$dr_data = array();
			
			$data['page_title'] = 'Receive Delivery';

			$items = DB::table('ebs_pull')
				->where('dr_number',$dr_number)
				->select('id','ordered_item','shipped_quantity')
				->get();

			$data['dr_detail'] =  DB::table('ebs_pull')
				->where('dr_number',$dr_number)
				->select('dr_number','customer_name','transaction_type','is_trade','status')
				->first();

			$data['stQuantity'] =  DB::table('ebs_pull')
				->where('dr_number',$dr_number)
				->sum('shipped_quantity');

			foreach ($items as $key => $value) {

				$serials = DB::table('serials')->where('ebs_pull_id',$value->id)->select('serial_number')->get();
				$item_detail = DB::table('items')->where('digits_code', $value->ordered_item)->first();

				$serial_data = array();
				foreach ($serials as $serial) {
					array_push($serial_data, $serial->serial_number);
				}

				$item_data[$key] = [
					'digits_code' => $value->ordered_item,
					'upc_code' => $item_detail->upc_code,
					'bea_item_id' => $item_detail->bea_item_id,
					'item_description' => $item_detail->item_description,
					'st_quantity' => $value->shipped_quantity,
					'st_serial_numbers' => $serial_data
				];
				
				$dr_data[$value->ordered_item] = $item_detail->has_serial;
			}

			$data['items'] = $item_data;
			$data['dr_items'] = json_encode($dr_data);
			
			// if($data['dr_detail']->status == 'RECEIVED'){
			// 	CRUDBooster::redirect(CRUDBooster::mainpath(),'DR# '.$data['dr_detail']->dr_number.' has already been received!','warning')->send();
			// }
			if(CRUDBooster::myChannel() == 4){ //online
				return view("delivery.onl-receive", $data);
			}
			elseif($data['dr_detail']->is_trade == 0){
				return view("delivery.sys-receive", $data);
			}
			else if(CRUDBooster::myBulkReceiving()[0] == 1){
			    \Log::info('test2 '. json_encode(CRUDBooster::myBulkReceiving()));
			    return view("delivery.sys-receive", $data);
			}
			else{
			    return view("delivery.receive", $data);//receive
			}
			
		}

		public function getDetail($dr_number)
		{
			$this->cbLoader();

			$data = array();
			$item_data = array();
			
			$data['page_title'] = 'Delivery Details';

			$items = DB::table('ebs_pull')
			->where('dr_number',$dr_number)
			->select('id','line_number','ordered_item','shipped_quantity')
			->orderBy('line_number','ASC')
			->get();

			$data['dr_detail'] =  DB::table('ebs_pull')
			->where('dr_number',$dr_number)
			->select('dr_number','customer_name','customer_po','transaction_type','st_document_number','si_document_number','received_at','status')
			->first();

			$data['stQuantity'] =  DB::table('ebs_pull')
			->where('dr_number',$dr_number)
			->sum('shipped_quantity');

			foreach ($items as $key => $value) {

				$serials = DB::table('serials')->where('ebs_pull_id',$value->id)->select('serial_number')->get();
				$item_detail = DB::table('items')->where('digits_code', $value->ordered_item)->first();

				$serial_data = array();
				foreach ($serials as $serial) {
					array_push($serial_data, $serial->serial_number);
				}

				$item_data[$key] = [
				    'line_number' => $value->line_number,
					'digits_code' => $value->ordered_item,
					'upc_code' => $item_detail->upc_code,
					'item_description' => $item_detail->item_description,
					'st_quantity' => $value->shipped_quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;
			
			return view("delivery.detail", $data);
		}

		public function itemSearch(Request $request)
		{
			$data = array();
            $data['status_no'] = 0;
            $data['message'] ='No item found!';
			
			$items = DB::table('ebs_pull')
				->where('ordered_item', $request->item_code)
				->where('dr_number', $request->dr_number)
				->first();
					
			if($items) {
				
				$data['status_no'] = 1;
				$data['message'] ='Item found!';
				$return_data['digits_code'] = $items->ordered_item;
				$return_data['serialized'] = $items->has_serial;
				$data['items'] = $return_data;	
			}
			            
            echo json_encode($data);
            exit;
		}

		public function serialSearch(Request $request)
		{
			$data = array();
            $data['status_no'] = 0;
			$data['message'] ='No item found!';
			$quantity = $request->quantity;
			
			$items = DB::table('ebs_pull')
				->where('ordered_item', $request->item_code)
				->where('dr_number', $request->dr_number)
				->first();

			$serial = DB::table('serials')
				->where('ebs_pull_id', $items->id)
				//->where('serial_number', $request->serial_number)
				->whereRaw("BINARY `serial_number`='{$request->serial_number}'")
				->first();
					
			if($serial && $serial->counter == 0) {
				$data['status_no'] = 1;
				$data['message'] ='Serial found!';
				$return_data['digits_code'] = $items->ordered_item;
				$data['serials'] = $return_data;

				DB::table('serials')
					->where('ebs_pull_id', $items->id)
					->where('serial_number', $request->serial_number)
					->update([
						'status' => 'RECEIVED',
						'counter' => ($serial->counter + 1)
					]);
			}
			            
            echo json_encode($data);
            exit;
		}

		public function resetSerialCounter(Request $request)
		{
			
			$items = DB::table('ebs_pull')
				->where('dr_number', $request->dr_number)
				->where('has_serial',1)
				->get();

			foreach ($items as $item) {
				DB::table('serials')
					->where('ebs_pull_id', $item->id)
					->update([
						'counter' => 0
					]);
			}

            echo json_encode($data['message'] = 'done');
            exit;
		}

		public function saveReceivingST(Request $request)
		{
			dd($request->all());
			$bea_record = DB::table('ebs_pull')->where('dr_number', $request->dr_number)->first();
			if($bea_record->status == 'RECEIVED'){
				CRUDBooster::redirect(CRUDBooster::mainpath(),'DR# '.$bea_record->dr_number.' has already been received!','warning')->send();
			}
			$record = false;
			if($request->transaction_type == 'SO'){
				$stores = DB::table('stores')->where('bea_so_store_name', $bea_record->customer_name)->first();
			}
			else{
				$stores = DB::table('stores')->where('bea_mo_store_name', $bea_record->customer_name)->first();
			}
			 //edited 20220318 due to soap error
			// $stockAdjustments = app(POSPullController::class)->getStockAdjustment($request->dr_number); //timeout while checking
							
			// if($stockAdjustments['data']['record']['fstatus_flag'] != 'POSTED'){
				//if(substr($stores->bea_mo_store_name, -3) != 'FBV'){
				if(!in_array(substr($stores->bea_mo_store_name, -3), ['FBV','FBD','OUT','CON','CRP','DLR'])){
				    if($request->transaction_type != 'MO-RMA'){
				        $postedSI = app(POSPushController::class)->postSI($request->dr_number);
				    }
				    else{
				        $postedSI = app(POSPushController::class)->postSI($request->dr_number,'DIGITSRMA');
				    }
				    
				}
			// }
			// check first if ST is existing
			$stockTransfers = app(POSPullController::class)->getStockTransferByRef($request->dr_number);
            
			if($stockTransfers['data']['record']['fstatus_flag'] != 'POSTED'){
				// if(substr($stores->bea_mo_store_name, -3) != 'FBV'){
				if(!in_array(substr($stores->bea_mo_store_name, -3), ['FBV','FBD','OUT','CON','CRP','DLR'])){
				    if($request->transaction_type != 'MO-RMA'){
					    app(POSPushController::class)->postST($request->dr_number, 'DIGITSWAREHOUSE', $stores->pos_warehouse);
				    }
					else{
					    app(POSPushController::class)->postST($request->dr_number, 'DIGITSRMA', $stores->pos_warehouse);
					}
				}	
				
				if($request->transaction_type == 'SO'){
					app(EBSPushController::class)->acceptedDate($request->dr_number);
					app(EBSPushController::class)->closeTrip($request->dr_number);

				}
				elseif(in_array($request->transaction_type,['MO-RMA','MO']) && substr($stores->bea_mo_store_name, -3) != 'FBD'){
				    if(empty(app(EBSPullController::class)->getShipmentRcvHeadersInterface($request->dr_number))){
					    app(EBSPushController::class)->dooReceiving($request->dr_number);
					    // app(EBSPushController::class)->receivingTransaction();
				    }
				    
				    if(substr($stores->bea_so_store_name, 0, 8) == 'GASHAPON'){
				        //get action
						$capsuleAction = DB::connection('gis')->table('capsule_action_types')->where('status','ACTIVE')
				            ->where('description','DR')->first();
						//get location
				        $location = DB::connection('gis')->table('locations')->where('status','ACTIVE')
				            ->where('location_name',$stores->bea_so_store_name)->first();

				        //get sublocation
				        $sublocation = DB::connection('gis')->table('sub_locations')->where('status','ACTIVE')
				            ->where('location_id',$location->id)->where('description','STOCK ROOM')->first();
				        
						
				        foreach($request->digits_code as $key_item => $value_item) {
							$existingcapsule = DB::connection('gis')->table('inventory_capsules')->where([
				                'item_code' => $value_item,
				                'locations_id' => $location->id
				            ])->first();

							$capsuleQty = $request->st_quantity[$key_item];
							if (!isset($existingcapsule->id)) {
								$capsules = DB::connection('gis')->table('inventory_capsules')->insertGetId([
									'item_code' => $value_item,
									'locations_id' => $location->id
								]);
								
								DB::connection('gis')->table('inventory_capsule_lines')->insert([
									'inventory_capsules_id' => $capsules,
									'sub_locations_id' => $sublocation->id,
									'qty' => $capsuleQty,
									'created_at' => date('Y-m-d H:i:s')
								]);
							}
							else{
								
								DB::connection('gis')->table('inventory_capsule_lines')->where([
									'inventory_capsules_id' => $existingcapsule->id,
									'sub_locations_id' => $sublocation->id
								])->update([
									'qty' => DB::raw("qty + $capsuleQty"),
									'updated_at' => date('Y-m-d H:i:s')
								]);
							}
							//add history
							DB::connection('gis')->table('history_capsules')->insert([
								'reference_number' => $request->dr_number,
								'item_code' => $value_item,
								'capsule_action_types_id' => $capsuleAction->id,
								'locations_id' => $location->id,
								'qty' => $capsuleQty,
								'created_at' => date('Y-m-d H:i:s')
							]);
				            
				        }
				    }
				    
				}
				elseif($request->transaction_type == 'MO' && substr($stores->bea_mo_store_name, -3) == 'FBD'){
					foreach ($request->digits_code as $key_item => $value_item) {
						app(EBSPushController::class)->createSIT($request->dr_number, 
							$request->bea_item_id[$key_item], 
							$request->st_quantity[$key_item], 
							'STAGINGMO',
							$stores->sit_subinventory,
							$bea_record->locator_id
						);
					}
				}
	
				DB::table('ebs_pull')->where('dr_number',$request->dr_number)->update([
					'io_reference' => (isset($request->io_reference)) ? $request->io_reference:null,
					'status' => 'RECEIVED',
					'received_at' => date('Y-m-d H:i:s')
				]);

				$record = true;
			}
			if($record){
			    CRUDBooster::insertLog(trans("crudbooster.dr_received", ['dr_number' =>$request->dr_number]));
				CRUDBooster::redirect(CRUDBooster::mainpath(),'DR# '.$request->dr_number.' has been received!','success')->send();
			}
			else{
				CRUDBooster::redirect(CRUDBooster::mainpath(),'DR# '.$request->dr_number.' has not been received!','danger')->send();
			}
			
		}


		public function checkDeliveryStatus(Request $request){
			$data = [];
			$data['status'] = 0;
			$bea_record = EBSPull::getDeliveryDetails($request->dr_number);
			if($bea_record->status == 'RECEIVED'){
				$data['status'] = 1;
				$data['message'] = 'DR# has already been received!';
			}
			return $data;
		}

		public function checkPOSStockAdjustment(Request $request){
			$data = [];
			$stockAdjustments = (new POSPullController)->getStockAdjustment($request->dr_number);
			$data['message'] = $stockAdjustments['data']['record']['fstatus_flag'];
			// if(sizeof($stockAdjustments['data']['record']) > 1)
			// 	$data['message'] = $stockAdjustments['data']['record'][0]['fstatus_flag']; //!= 'POSTED'
			return $data;
		}

		public function checkPOSStockTransfer(Request $request){
			$data = [];
			$stockTransfers = (new POSPullController)->getStockTransferByRef($request->dr_number);
			$data['message'] = $stockTransfers['data']['record']['fstatus_flag'];
			// if(sizeof($stockTransfers['data']['record']) > 1)
			// 	$data['message'] = $stockTransfers['data']['record'][0]['fstatus_flag'];
			return $data;
		}

		public function createPOSAdj(Request $request) {
			$data = [];
			$data['status'] = 0;
			$bea_record = EBSPull::getDeliveryDetails($request->dr_number);
			$stores = StoreName::getMoCustomerName($bea_record->customer_name);

			if($request->transaction_type == 'SO'){
				$stores = StoreName::getSoCustomerName($bea_record->customer_name);
			}

			if(!in_array(substr($stores->bea_mo_store_name, -3), ['FBV','FBD','OUT','CON','CRP','DLR'])){
				if($request->transaction_type != 'MO-RMA'){
					$postedSI = (new POSPushController)->postSI($request->dr_number);
				}
				else{
					$postedSI = (new POSPushController)->postSI($request->dr_number,'DIGITSRMA');
				}
				$data['message'] = $postedSI['data']['record']['fdocument_no'];
				$data['status'] = $postedSI['data']['record']['fstatus_flag'];
			}

			return $data;
		}

		public function createPOSSto(Request $request) {
			$data = [];
			$data['status'] = 0;
			$bea_record = EBSPull::getDeliveryDetails($request->dr_number);
			$stores = StoreName::getMoCustomerName($bea_record->customer_name);

			if(!in_array(substr($stores->bea_mo_store_name, -3), ['FBV','FBD','OUT','CON','CRP','DLR'])){
				if($request->transaction_type != 'MO-RMA'){
					$postedST = (new POSPushController)->postST($request->dr_number, 'DIGITSWAREHOUSE', $stores->pos_warehouse);
				}
				else{
					$postedST = (new POSPushController)->postST($request->dr_number, 'DIGITSRMA', $stores->pos_warehouse);
				}
				$data['message'] = $postedST['data']['record']['fdocument_no'];
				$data['status'] = $postedST['data']['record']['fstatus_flag'];
			}

			return $data;
		}

		public function createBEADOT(Request $request) {
			$data = [];
			$data['status'] = 0;

			EBSPull::where('dr_number',$request->dr_number)
			->update([
				'io_reference' => (isset($request->io_reference)) ? $request->io_reference:null,
				'status' => 'RECEIVED',
				'received_at' => date('Y-m-d H:i:s')
			]);

			CRUDBooster::insertLog(trans("crudbooster.dr_received", ['dr_number' =>$request->dr_number]));
			CRUDBooster::redirect(CRUDBooster::mainpath(),'DR# '.$request->dr_number.' has been received!','success')->send();

			// $data['status'] = 1;
			// $data['message'] = `DR# $request->dr_number has been received successfully!`;
			// return $data;
		}

		public function getPOSItemDetails(Request $request){
			$data = [];
			$itemDetail = (new POSPullController)->getProduct($request->ordered_item);
			$data['item_type'] = $itemDetail['data']['record']['fproduct_type'];
			$data['item_price'] = $itemDetail['data']['record']['flist_price'];
			return $data;
		}

		public function getBEAItemDetails(Request $request){
			\Log::debug($request->all());
			$data = [];
			$data['beaItemDetail'] = (new EBSPullController)->getProductSerials($request->ordered_item, $request->dr_number);
            $data['serials'] = (new EBSPullController)->getItemSerials($request->ordered_item, $request->dr_number);
            $data['unique_serials'] = (new EBSPullController)->getDuplicateSerials($request->ordered_item, $request->dr_number);
			return $data;
		}
	}
<?php namespace App\Http\Controllers;

	use Session;
	use Illuminate\Http\Request;
	// use Illuminate\Support\Facades\Request;
	use DB;
	use CRUDBooster;
	use App\ApprovalMatrix;
	use Maatwebsite\Excel\Facades\Excel;

	class AdminDeliveryController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "dr_number";
			$this->limit = "20";
			$this->orderby = "dr_number,asc";
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
			$this->col[] = ["label"=>"Received ST #","name"=>"st_document_number"];
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
            if(CRUDBooster::isSuperadmin()){
                
            	$this->addaction[] = ['title'=>'Re-run Receiving DR',
            	'url'=>CRUDBooster::mainpath().'/receive_dr/[dr_number]',
            	'icon'=>'fa fa-refresh',
            	'color'=>'warning',
            	'showIf'=>"[st_document_number]=='' and [status]=='RECEIVED'",
            	'confirmation'=>'yes',
            	'confirmation_title'=>'Confirm Re-run DR Receiving',
            	'confirmation_text'=>'Are you sure to re-run DR receiving?'];
            	
            	$this->addaction[] = ['title'=>'Push DOT Receiving Interface',
            	'url'=>CRUDBooster::mainpath().'/push_dotr_interface/[dr_number]',
            	'icon'=>'fa fa-upload',
            	'color'=>'warning',
            	'showIf'=>"[status]=='RECEIVED'",
            	'confirmation'=>'yes',
            	'confirmation_title'=>'Confirm push DOT receiving interface',
            	'confirmation_text'=>'Are you sure to push DOT receiving interface?'];
            	
				$this->addaction[] = ['title'=>'Stock out DR',
				'url'=>CRUDBooster::mainpath().'/stockout_dr/[st_document_number]/[customer_name]',
				'icon'=>'fa fa-times',
				'color'=>'warning',
				'showIf'=>"[st_document_number]!='' and [status]!='VOID'",
				'confirmation'=>'yes',
				'confirmation_title'=>'Confirm Stock out DR',
				'confirmation_text'=>'Are you sure to Stock out DR?'];
				
				$this->addaction[] = ['title'=>'Edit DR',
				'url'=>CRUDBooster::mainpath().'/edit_stockout_dr/[dr_number]',
				'icon'=>'fa fa-pencil',
				'color'=>'info',
				'showIf'=>"[st_document_number]!='' and [status]!='VOID'",
				'confirmation'=>'yes',
				'confirmation_title'=>'Confirm Edit DR',
				'confirmation_text'=>'Are you sure to Edit DR?'];
				
				/*app(EBSPushController::class)->dooReceiving($request->dr_number);*/
			}
			//---end---2021-11-03
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
			if(CRUDBooster::getCurrentMethod() == 'getIndex') {
				$this->index_button[] = ["title"=>"Export Delivery with Serial","label"=>"Export Delivery with Serial",'color'=>'info',"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export-dr-serialized').'?'.urldecode(http_build_query(@$_GET))];
				$this->index_button[] = ["title"=>"Export Delivery","label"=>"Export Delivery",'color'=>'primary',"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export-dr').'?'.urldecode(http_build_query(@$_GET))];
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
				if(in_array(CRUDBooster::myPrivilegeName(), ["Approver","Franchise Approver"])){
					//get approval matrix
					$approvalMatrix = ApprovalMatrix::where('approval_matrix.cms_users_id', CRUDBooster::myId())->get();
					
					$approval_array = array();
					foreach($approvalMatrix as $matrix){
						array_push($approval_array, $matrix->store_list);
					}
					$approval_string = implode(",",$approval_array);
					$storeList = array_map('intval',explode(",",$approval_string));
	
					$query->whereIn('ebs_pull.stores_id', array_values((array)$storeList))
						->where('ebs_pull.status','!=','PROCESSING')
						->select('ebs_pull.dr_number','ebs_pull.customer_name','ebs_pull.status')->distinct();
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Retail Ops"])){
				    if(empty($store)){
    					$query->select('ebs_pull.dr_number','ebs_pull.customer_name','ebs_pull.status')
    						->where('ebs_pull.status','!=','PROCESSING')
    						->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'RTL')
    						->distinct();
				    }
				    else{
				        $query->select('ebs_pull.dr_number','ebs_pull.customer_name','ebs_pull.status')
    						->where('ebs_pull.status','!=','PROCESSING')
    						->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'RTL')
    						->whereIn('ebs_pull.customer_name', function ($sub_query) use ($store) {
    						    $sub_query->select('ebs_pull.customer_name')->from('ebs_pull')
    						    ->where('ebs_pull.customer_name',$store->bea_mo_store_name)
    						    ->orWhere('ebs_pull.customer_name',$store->bea_so_store_name)->distinct()->get()->toArray();
    						})->distinct();
				    }
				}
				
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Online Ops","Online Viewer"])){
					$query->select('ebs_pull.dr_number','ebs_pull.customer_name','ebs_pull.status')
						->where('ebs_pull.status','!=','PROCESSING')
						->whereIn('ebs_pull.customer_name', function ($sub_query) {
						    $sub_query->select('ebs_pull.customer_name')->from('ebs_pull')
						    ->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FBD')
						    ->orWhere(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FBV')->distinct()->get()->toArray();
						})->distinct();
				}
				
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Franchise Ops"])){
					$query->select('ebs_pull.dr_number','ebs_pull.customer_name','ebs_pull.status')
						->where('ebs_pull.status','!=','PROCESSING')
						->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FRA')
						->distinct();
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Rtl Fra Ops"])){
					$query->select('ebs_pull.dr_number','ebs_pull.customer_name','ebs_pull.status')
						->where('ebs_pull.status','!=','PROCESSING')
						->whereIn('ebs_pull.customer_name', function ($sub_query) {
						    $sub_query->select('ebs_pull.customer_name')->from('ebs_pull')
						    ->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FRA')
						    ->orWhere(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'RTL')->distinct()->get()->toArray();
						})->distinct();
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Reports"])){
					$query->select('ebs_pull.dr_number','ebs_pull.customer_name','ebs_pull.status')
						->where('ebs_pull.status','!=','PROCESSING')
						->whereIn('ebs_pull.customer_name', function ($sub_query) {
						    $sub_query->select('ebs_pull.customer_name')->from('ebs_pull')
						    ->whereIn(DB::raw('substr(ebs_pull.customer_name, -3)'), ['RTL','FRA','FBD','FBV'])
						    ->distinct()->get()->toArray();
						})->distinct();
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Rtl Onl Viewer"])){
					$query->select('ebs_pull.dr_number','ebs_pull.customer_name','ebs_pull.status')
						->where('ebs_pull.status','!=','PROCESSING')
						->whereIn('ebs_pull.customer_name', function ($sub_query) {
						    $sub_query->select('ebs_pull.customer_name')->from('ebs_pull')
						    ->whereIn(DB::raw('substr(ebs_pull.customer_name, -3)'), ['RTL','FBD','FBV'])
						    ->distinct()->get()->toArray();
						})->distinct();
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Audit","Inventory Control","Merch"])){
					$query->select('ebs_pull.dr_number','ebs_pull.customer_name','ebs_pull.status')
						->where('ebs_pull.status','!=','PROCESSING')
						->distinct();
				}
				else {
				    $stores = DB::table('stores')->whereIn('id', CRUDBooster::myStore())->get();
    				$storeAccess = array();
    				foreach($stores as $store ){
    				    array_push($storeAccess, $store->bea_mo_store_name);
    				    array_push($storeAccess, $store->bea_so_store_name);
    				}
				
					$query->select('ebs_pull.dr_number','ebs_pull.customer_name','ebs_pull.status') 
						->where('ebs_pull.status','!=','PROCESSING')
						->whereIn('ebs_pull.customer_name', function ($sub_query) use ($storeAccess) { //use $store
						    $sub_query->select('ebs_pull.customer_name')->from('ebs_pull')
						    ->whereIn('ebs_pull.customer_name', $storeAccess)
						  //  ->where('ebs_pull.customer_name',$store->bea_mo_store_name)
						  //  ->orWhere('ebs_pull.customer_name',$store->bea_so_store_name)
						    ->distinct()->get()->toArray();
						})->distinct();   
				}
			}
			else{
				$query->select('ebs_pull.dr_number','ebs_pull.customer_name','ebs_pull.status')->distinct();
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
				else if($column_value == "RECEIVED"){
					$column_value = '<span class="label label-success">RECEIVED</span>';
				}
				else if($column_value == "VOID"){
					$column_value = '<span class="label label-danger">VOID</span>';
				}
				else if($column_value == "CLOSED"){
					$column_value = '<span class="label label-danger">CLOSED</span>';
				}
				else if($column_value == "PROCESSING"){
					$column_value = '<span class="label label-info">PROCESSING</span>';
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
			
			$this->cbView("delivery.detail", $data);
		}
		
		public function drReceivingST($dr_number)
		{
			$dr_records = DB::table('ebs_pull')->where('dr_number', $dr_number)->get();
			app(POSPushController::class)->postSI($dr_number);

			if($dr_records[0]->transaction_type == 'SO'){
				$stores = DB::table('stores')->where('bea_so_store_name', $dr_records[0]->customer_name)->first();
			}
			else{
				$stores = DB::table('stores')->where('bea_mo_store_name', $dr_records[0]->customer_name)->first();
			}

			$postedST = app(POSPushController::class)->postST($dr_number, 'DIGITSWAREHOUSE', $stores->pos_warehouse);
					
// 			if($dr_records[0]->transaction_type == 'SO'){
// 				app(EBSPushController::class)->acceptedDate($dr_number);
// 				app(EBSPushController::class)->closeTrip($dr_number);

// 			}
// 			elseif($dr_records[0]->transaction_type == 'MO' && substr($stores->bea_mo_store_name, -3) != 'FBD'){
// 				app(EBSPushController::class)->dooReceiving($dr_number);
// 				// app(EBSPushController::class)->receivingTransaction();
// 			}
// 			elseif($dr_records[0]->transaction_type == 'MO' && substr($stores->bea_mo_store_name, -3) == 'FBD'){
// 				foreach ($dr_records as $dr) {
// 					app(EBSPushController::class)->createSIT($dr_number, 
// 						$dr->bea_item_id, 
// 						$dr->st_quantity, 
// 						'STAGINGMO',
// 						$stores->sit_subinventory,
// 						$dr_records[0]->locator_id
// 					);
// 				}
// 			}
			
			CRUDBooster::redirect(CRUDBooster::mainpath(),'DR# '.$dr_number.' has been received!','success')->send();

		}
		
		public function drStockout($st_number,$customer_name)
		{
			$posItemDetails = array();

			$drItems = DB::table('ebs_pull')
				->where('st_document_number',$st_number)->get();

			$store_goodwh = DB::table('stores')->where('bea_mo_store_name', $customer_name)->first();
			
			foreach ($drItems as $key_item => $value_item) {
				
				$price = DB::table('items')->where('digits_code',$value_item->ordered_item)->value('store_cost');

				if($value_item->has_serial == 1){

					$drSerials = DB::table('serials')->where('ebs_pull_id',$value_item->id)->get();

					foreach ($drSerials as $key_serial => $value_serial) {
						
						$posItemDetails[$value_item->ordered_item.'-'.$key_serial] = [
							'item_code' => $value_item->ordered_item,
							'quantity' => 1,
							'serial_number' => $value_serial->serial_number,
							'item_price' => $price
						];
					}
					
				}
				else{
					
					$posItemDetails[$value_item->ordered_item.'-'.$key_item] = [
						'item_code' => $value_item->ordered_item,
						'quantity' => $value_item->quantity,
						'item_price' => $price
					];
					
				}
			}
			
			$drOut = app(POSPushController::class)->posCreateStockAdjustmentOut($st_number,$store_goodwh->pos_warehouse, $posItemDetails);
			
			if(!empty($drOut['data']['record']['fdocument_no'])){
				CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$st_number.' has been stock out from '.$store_goodwh->pos_warehouse.'! with REF# '.$drOut['data']['record']['fdocument_no'],'success')->send();
			}else{
				CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! '.$drOut['data']['record']['errors']['error'] ,'danger')->send();
			}
		}
		
		public function getdrStockout($dr_number)
		{
			$this->cbLoader();

			$data = array();
			$item_data = array();
			
			$data['page_title'] = 'Edit Delivery';

			$items = DB::table('ebs_pull')
			->where('dr_number',$dr_number)
			->select('id','ordered_item','shipped_quantity')
			->get();

			$data['dr_detail'] =  DB::table('ebs_pull')
			->where('dr_number',$dr_number)
			->select('dr_number','customer_name','transaction_type','st_document_number')
			->first();

			$data['store_detail'] = DB::table('stores')->where('bea_mo_store_name',$data['dr_detail']->customer_name)->first();

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
					'item_description' => $item_detail->item_description,
					'item_price' => $item_detail->store_cost,
					'st_quantity' => $value->shipped_quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;
			$this->cbView("delivery.edit-stockout", $data);
		}

		public function saveDRStockout(Request $request)
		{
			$posItemDetails = array();

			foreach ($request->digits_code as $key => $value) {
				$sItems = 's'.$value;
				$gItems = 'g'.$value;

				if($request->$sItems){

					foreach ($request->$sItems as $key_serial => $value_serial) {
						
						$posItemDetails[$value.'-'.$key_serial] = [
							'item_code' => $value,
							'quantity' => 1,
							'serial_number' => $value_serial,
							'item_price' => $request->item_price[$key]
						];
					}
				}

				elseif($request->$gItems){
					$posItemDetails[$value.'-'.$key] = [
						'item_code' => $value,
						'quantity' => $request->st_quantity[$key],
						'item_price' => $request->item_price[$key]
					];
				}
			}
			
			$drOut = app(POSPushController::class)->posCreateStockAdjustmentOut($request->st_number,$request->warehouse, $posItemDetails);
			
			if(!empty($drOut['data']['record']['fdocument_no'])){
				CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' has been stock out from '.$request->warehouse.'! with REF# '.$drOut['data']['record']['fdocument_no'],'success')->send();
			}else{
				CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! '.$drOut['data']['record']['errors']['error'] ,'danger')->send();
			}
		}
		
		public function dotrPushInterface($dr_number){
		    app(EBSPushController::class)->dooReceiving($dr_number);
		    CRUDBooster::redirect(CRUDBooster::mainpath(),'DR# '.$dr_number. 'has been pushed to receiving interface');
		}
		
		public function exportDelivery(Request $request)
		{
			$store = DB::table('stores')->where('id', CRUDBooster::myStore())->first();
			ini_set('memory_limit', -1);
			
			
			$dr_item = DB::table('ebs_pull')->select(
				'ebs_pull.dr_number',
				'ebs_pull.st_document_number as st_number',
				'ebs_pull.ordered_item as digits_code',
				'items.upc_code',
				'items.item_description',
				'ebs_pull.customer_name as destination',
				'ebs_pull.shipped_quantity as dr_quantity',
				'ebs_pull.created_at as created_date',
				'ebs_pull.received_at as received_date',
				'ebs_pull.status')
			->leftJoin('items', 'ebs_pull.ordered_item', '=', 'items.digits_code');
			
			\Log::info(\Request::get('filter_column'));
			
			if(\Request::get('filter_column')) {

				$filter_column = \Request::get('filter_column');

				$dr_item->where(function($w) use ($filter_column,$fc) {
					foreach($filter_column as $key=>$fc) {

						$value = @$fc['value'];
						$type  = @$fc['type'];

						if($type == 'empty') {
							$w->whereNull($key)->orWhere($key,'');
							continue;
						}

						if($value=='' || $type=='') continue;

						if($type == 'between') continue;

						switch($type) {
							default:
								if($key && $type && $value) $w->where($key,$type,$value);
							break;
							case 'like':
							case 'not like':
								$value = '%'.$value.'%';
								if($key && $type && $value) $w->where($key,$type,$value);
							break;
							case 'in':
							case 'not in':
								if($value) {
									$value = explode(',',$value);
									if($key && $value) $w->whereIn($key,$value);
								}
							break;
						}
					}
				});

				foreach($filter_column as $key=>$fc) {
					$value = @$fc['value'];
					$type  = @$fc['type'];
					$sorting = @$fc['sorting'];

					if($sorting!='') {
						if($key) {
							$dr_item->orderby($key,$sorting);
							$filter_is_orderby = true;
						}
					}

					if ($type=='between') {
						if($key && $value) $dr_item->whereBetween($key,$value);
					}

					else {
						continue;
					}
				}
			}

			if(!CRUDBooster::isSuperAdmin() && !in_array(CRUDBooster::myPrivilegeName(),
			    ["Retail Ops","Reports","Franchise Ops",
			    "Rtl Fra Ops","Rtl Onl Viewer","Audit",
			    "Inventory Control","Merch","Online Ops",
			    "Online Viewer","Approver","Franchise Approver"])){
			        
					$dr_item->where('ebs_pull.customer_name',$store->bea_mo_store_name)
						->orWhere('ebs_pull.customer_name',$store->bea_so_store_name);
			}
			if(in_array(CRUDBooster::myPrivilegeName(), ["Approver","Franchise Approver"])){
				//get approval matrix
				$approvalMatrix = ApprovalMatrix::where('approval_matrix.cms_users_id', CRUDBooster::myId())->get();
				
				$approval_array = array();
				foreach($approvalMatrix as $matrix){
					array_push($approval_array, $matrix->store_list);
				}
				$approval_string = implode(",",$approval_array);
				$storeList = array_map('intval',explode(",",$approval_string));

				$dr_item->whereIn('ebs_pull.stores_id', array_values((array)$storeList))
					->where('ebs_pull.status','!=','PROCESSING');
			}
			elseif(in_array(CRUDBooster::myPrivilegeName(),["Franchise Ops"])){
				$dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FRA');
			}
			elseif (in_array(CRUDBooster::myPrivilegeName(),["Retail Ops"])) {
			    if(empty($store)){
				    $dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'RTL');
			    }
			    else{
			        $dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'RTL')
			        ->whereIn('ebs_pull.customer_name', function ($sub_query) use ($store) {
					    $sub_query->select('ebs_pull.customer_name')->from('ebs_pull')
					    ->where('ebs_pull.customer_name',$store->bea_mo_store_name)
					    ->orWhere('ebs_pull.customer_name',$store->bea_so_store_name)->distinct()->get()->toArray();
					});
			    }
			}
			elseif (in_array(CRUDBooster::myPrivilegeName(),["Rtl Fra Ops"])) {
				$dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'RTL')
				->orWhere(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FRA');
			}
			elseif (in_array(CRUDBooster::myPrivilegeName(),["Reports"])) {
				$dr_item->whereIn(DB::raw('substr(ebs_pull.customer_name, -3)'),['RTL','FRA','FBD','FBV']);
			}
			elseif (in_array(CRUDBooster::myPrivilegeName(),["Rtl Onl Viewer"])) {
				$dr_item->whereIn(DB::raw('substr(ebs_pull.customer_name, -3)'), ['RTL','FBD','FBV']);
			}
			elseif (in_array(CRUDBooster::myPrivilegeName(),["Online Ops","Online Viewer"])) {
				$dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FBD')
				->orWhere(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FBV');
			}

			$dr_item->orderBy('ebs_pull.dr_number', 'asc');
			ini_set('max_execution_time', 0);
			$drItems = $dr_item->get();
			
			$headings = array('DR #',
				'ST #',
				'DIGITS CODE',
				'UPC CODE',
				'ITEM DESCRIPTION',
				'SOURCE',
				'DESTINATION',
				'QTY',
				'CREATED DATE',
				'RECEIVED DATE',
				'STATUS');

			foreach($drItems as $item) {

				$items_array[] = array(
					$item->dr_number,
					$item->st_number,
					$item->digits_code,	
					'="'.$item->upc_code.'"',			
					$item->item_description,	
					'DIGITSWAREHOUSE',
					$item->destination,
					$item->dr_quantity,
					$item->created_date,
					$item->received_date,
					$item->status
				);
			}
		    ini_set('max_execution_time', 0);			
			Excel::create('Export Delivery - '.date("Ymd H:i:sa"), function($excel) use ($headings,$items_array){
				$excel->sheet('dr', function($sheet) use ($headings,$items_array){
					// Set auto size for sheet
					$sheet->setAutoSize(true);
					$sheet->setColumnFormat(array(
						'D' => '@',
					));
					
					$sheet->fromArray($items_array, null, 'A1', false, false);
					$sheet->prependRow(1, $headings);
					$sheet->row(1, function($row) {
						$row->setBackground('#FFFF00');
						$row->setAlignment('center');
					});
					
				});
			})->export('xlsx');
		}

		public function exportDeliverySerialized(Request $request)
		{
			$store = DB::table('stores')->where('id', CRUDBooster::myStore())->first();
			ini_set('memory_limit', -1);
			ini_set('max_execution_time', 0);
			Excel::create('Export Delivery with Serial - '.date("Ymd H:i:sa"), function($excel) use ($store){
				$excel->sheet('dr-serialzed', function($sheet) use ($store){
					// Set auto size for sheet
					$sheet->setAutoSize(true);
					$sheet->setColumnFormat(array(
						'D' => '@',
					));
	
					$dr_item = DB::table('ebs_pull')->select(
						'ebs_pull.dr_number',
						'ebs_pull.st_document_number as st_number',
						'ebs_pull.ordered_item as digits_code',
						'items.upc_code',
						'items.item_description',
						'ebs_pull.customer_name as destination',
						'ebs_pull.shipped_quantity as dr_quantity',
						'serials.serial_number',
						'ebs_pull.created_at as created_date',
						'ebs_pull.received_at as received_date',
						'ebs_pull.status')
					->leftJoin('items', 'ebs_pull.ordered_item', '=', 'items.digits_code')
					->leftJoin('serials', 'ebs_pull.id', '=', 'serials.ebs_pull_id');
					
					\Log::info(\Request::get('filter_column'));
					
					if(\Request::get('filter_column')) {

						$filter_column = \Request::get('filter_column');
	
						$dr_item->where(function($w) use ($filter_column,$fc) {
							foreach($filter_column as $key=>$fc) {
	
								$value = @$fc['value'];
								$type  = @$fc['type'];
	
								if($type == 'empty') {
									$w->whereNull($key)->orWhere($key,'');
									continue;
								}
	
								if($value=='' || $type=='') continue;
	
								if($type == 'between') continue;
	
								switch($type) {
									default:
										if($key && $type && $value) $w->where($key,$type,$value);
									break;
									case 'like':
									case 'not like':
										$value = '%'.$value.'%';
										if($key && $type && $value) $w->where($key,$type,$value);
									break;
									case 'in':
									case 'not in':
										if($value) {
											$value = explode(',',$value);
											if($key && $value) $w->whereIn($key,$value);
										}
									break;
								}
							}
						});
	
						foreach($filter_column as $key=>$fc) {
							$value = @$fc['value'];
							$type  = @$fc['type'];
							$sorting = @$fc['sorting'];
	
							if($sorting!='') {
								if($key) {
									$dr_item->orderby($key,$sorting);
									$filter_is_orderby = true;
								}
							}
	
							if ($type=='between') {
								if($key && $value) $dr_item->whereBetween($key,$value);
							}
	
							else {
								continue;
							}
						}
					}
					if(!CRUDBooster::isSuperAdmin() && !in_array(CRUDBooster::myPrivilegeName(),["Retail Ops","Franchise Ops","Rtl Fra Ops","Rtl Onl Viewer","Audit","Inventory Control","Merch","Online Ops","Online Viewer","Approver","Franchise Approver"])){
						$dr_item->where('ebs_pull.customer_name',$store->bea_mo_store_name)
							->orWhere('ebs_pull.customer_name',$store->bea_so_store_name);
					}
					if(in_array(CRUDBooster::myPrivilegeName(), ["Approver","Franchise Approver"])){
    					//get approval matrix
    					$approvalMatrix = ApprovalMatrix::where('approval_matrix.cms_users_id', CRUDBooster::myId())->get();
    					
    					$approval_array = array();
    					foreach($approvalMatrix as $matrix){
    						array_push($approval_array, $matrix->store_list);
    					}
    					$approval_string = implode(",",$approval_array);
    					$storeList = array_map('intval',explode(",",$approval_string));
    	
    					$dr_item->whereIn('ebs_pull.stores_id', array_values((array)$storeList))
    						->where('ebs_pull.status','!=','PROCESSING');
    				}
					elseif(in_array(CRUDBooster::myPrivilegeName(),["Franchise Ops"])){
						$dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FRA');
					}
					elseif (in_array(CRUDBooster::myPrivilegeName(),["Retail Ops"])) {
					    if(empty($store)){
						    $dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'RTL');
					    }
					    else{
					        $dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'RTL')
					        ->whereIn('ebs_pull.customer_name', function ($sub_query) use ($store) {
        					    $sub_query->select('ebs_pull.customer_name')->from('ebs_pull')
        					    ->where('ebs_pull.customer_name',$store->bea_mo_store_name)
        					    ->orWhere('ebs_pull.customer_name',$store->bea_so_store_name)->distinct()->get()->toArray();
        					});
					    }
					}
					elseif (in_array(CRUDBooster::myPrivilegeName(),["Rtl Fra Ops"])) {
						$dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'RTL')
						->orWhere(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FRA');
					}
					elseif (in_array(CRUDBooster::myPrivilegeName(),["Rtl Onl Viewer"])) {
						$dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'RTL')
						->orWhere(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FBD')
						->orWhere(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FBV');
					}
					elseif (in_array(CRUDBooster::myPrivilegeName(),["Online Ops","Online Viewer"])) {
						$dr_item->where(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FBD')
						->orWhere(DB::raw('substr(ebs_pull.customer_name, -3)'), '=', 'FBV');
					}
					$dr_item->orderBy('ebs_pull.dr_number', 'asc');
					$drItems = $dr_item->get();
					
					$headings = array('DR #',
						'ST #',
						'DIGITS CODE',
						'UPC CODE',
						'ITEM DESCRIPTION',
						'SOURCE',
						'DESTINATION',
						'QTY',
						'SERIAL #',
						'CREATED DATE',
						'RECEIVED DATE',
						'STATUS');

					foreach($drItems as $item) {
	
						$items_array[] = array(
							$item->dr_number,
							$item->st_number,
							$item->digits_code,	
							'="'.$item->upc_code.'"',			
							$item->item_description,
							'DIGITSWAREHOUSE',
							$item->destination,
							(empty($item->serial_number)) ? $item->dr_quantity : 1,
							$item->serial_number,
							$item->created_date,
							$item->received_date,
							$item->status
						);
					}
					
					$sheet->fromArray($items_array, null, 'A1', false, false);
					$sheet->prependRow(1, $headings);
					$sheet->row(1, function($row) {
						$row->setBackground('#FFFF00');
						$row->setAlignment('center');
					});
					
				});
			})->export('xlsx');
		}

	}
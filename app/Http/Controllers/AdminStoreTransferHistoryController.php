<?php namespace App\Http\Controllers;

	use Session;
	use DB;
	use CRUDBooster;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\File;
	use Illuminate\Http\Request;
	use App\ApprovalMatrix;
	use Excel;

	class AdminStoreTransferHistoryController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->table = "pos_pull";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"ST #","name"=>"st_document_number"];
			$this->col[] = ["label"=>"Received ST #","name"=>"received_st_number"];
			$this->col[] = ["label"=>"From WH","name"=>"stores_id","join"=>"stores,pos_warehouse_name"];
			$this->col[] = ["label"=>"To WH","name"=>"stores_id_destination","join"=>"stores,pos_warehouse_name"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Transport By","name"=>"transport_types_id","join"=>"transport_types,transport_type"];
			if (in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
				    $this->col[] = ["label"=>"Scheduled Date","name"=>"scheduled_at"];
			}
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
			if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])){
				$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('print').'/[st_document_number]','icon'=>'fa fa-print','color'=>'info','showIf'=>"[status]=='FOR RECEIVING'"];
			}
			if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(), ["Requestor", "Requestor II", "Gashapon Requestor"])){
				$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('print').'/[st_document_number]','icon'=>'fa fa-print','color'=>'info','showIf'=>"[status]=='PENDING'"];
			}
			if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(), ["Online WSDM"])){
				$this->addaction[] = ['title'=>'Print Pick List','url'=>CRUDBooster::mainpath('print-picklist').'/[st_document_number]','icon'=>'fa fa-print','color'=>'info'];
			}
			
			$this->addaction[] = ['title'=>'Details','url'=>CRUDBooster::mainpath('details').'/[st_document_number]?return_url='.urlencode(\Request::fullUrl()),'icon'=>'fa fa-eye','color'=>'primary'];
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
            if(CRUDBooster::myPrivilegeName() == "Online WSDM"){
				$this->button_selected[] = ['label'=>'Print Picklist', 'icon'=>'fa fa-print', 'name'=>'print_picklist'];
			}
	                
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
			if(CRUDBooster::getCurrentMethod() == 'getIndex'){
				$this->index_button[] = ["title"=>"Export STS with Serial","label"=>"Export STS with Serial",'color'=>'info',"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export-sts-serialized').'?'.urldecode(http_build_query(@$_GET))];
				$this->index_button[] = ["title"=>"Export STS","label"=>"Export STS",'color'=>'primary',"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export-sts').'?'.urldecode(http_build_query(@$_GET))];
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
				$store = DB::table('stores')->whereIn('id', CRUDBooster::myStore())->first();
				
				if(in_array(CRUDBooster::myPrivilegeName(), ["Approver","Franchise Approver"])){
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
						->select('pos_pull.st_document_number','pos_pull.wh_from','pos_pull.wh_to','pos_pull.status')->distinct();

				}
				elseif (in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
					$query->select('pos_pull.st_document_number','pos_pull.wh_from','pos_pull.wh_to','pos_pull.status')->where('pos_pull.transport_types_id',1)->distinct();
				}
				
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Online Ops","Retail Ops","Franchise Ops","Online Viewer"])) {
				    if(empty($store)){
    					$query->select('pos_pull.st_document_number','pos_pull.wh_from','pos_pull.wh_to','pos_pull.status')
    					->where('pos_pull.channel_id', CRUDBooster::myChannel())->distinct();  
				    }
				    else{
				        $query->select('pos_pull.st_document_number','pos_pull.wh_from','pos_pull.wh_to','pos_pull.status')
    					->where('pos_pull.channel_id', CRUDBooster::myChannel())
    					->where(function($subquery) use ($store) {
                            $subquery->whereIn('pos_pull.stores_id',CRUDBooster::myStore())->orWhere('pos_pull.wh_to',$store->pos_warehouse);
                        })->distinct();
				    }
				}
				
				elseif(CRUDBooster::myPrivilegeName() == "Rtl Fra Ops") {
					$query->select('pos_pull.st_document_number','pos_pull.wh_from','pos_pull.wh_to','pos_pull.status')
					->whereIn('pos_pull.channel_id', [1,2])->distinct();   
				}
				
				elseif(CRUDBooster::myPrivilegeName() == "Reports") {
					$query->select('pos_pull.st_document_number','pos_pull.wh_from','pos_pull.wh_to','pos_pull.status')
					->whereIn('pos_pull.channel_id', [1,2,4])->distinct();   
				}
				
				elseif(CRUDBooster::myPrivilegeName() == "Rtl Onl Viewer") {
					$query->select('pos_pull.st_document_number','pos_pull.wh_from','pos_pull.wh_to','pos_pull.status')
					->whereIn('pos_pull.channel_id', [1,4])->distinct();   
				}

				elseif(CRUDBooster::myPrivilegeName() == "Online WSDM") {
					$query->select('pos_pull.st_document_number','pos_pull.wh_from','pos_pull.wh_to','pos_pull.status')
					->whereIn('pos_pull.stores_id', CRUDBooster::myStore())->distinct();   
				}
				
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Audit","Inventory Control","Merch"])){
				    $query->select('pos_pull.st_document_number','pos_pull.wh_from','pos_pull.wh_to','pos_pull.status')->distinct();
				}
				else{
					$query->select('pos_pull.st_document_number','pos_pull.wh_from','pos_pull.wh_to','pos_pull.status','pos_pull.created_date')
					->where(function($subquery) use ($store) {
                        $subquery->whereIn('pos_pull.stores_id',CRUDBooster::myStore())->orWhere('pos_pull.wh_to',$store->pos_warehouse);
                    })->distinct();
				}
				
			}
			else{
			    $query->select('pos_pull.st_document_number','pos_pull.wh_from','pos_pull.wh_to','pos_pull.status')->distinct();
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

		public function printPicklist($st_number)
		{
			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'STS Details';

			$data['stDetails'] = DB::table('pos_pull')->where('st_document_number', $st_number)->get();

			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id)->first();

			$data['transfer_to'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id_destination)->first();

			$items = DB::table('pos_pull')
			->where('st_document_number',$st_number)
			->select('id','item_code','quantity')
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
					'item_description' => $item_detail->item_description,
					'price' => $item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;

			$this->cbView("stock-transfer.print-picklist", $data);
			
		}
		
		public function getPrint($st_number)
		{
			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'STS Details';

			$data['stDetails'] = DB::table('pos_pull')->where('st_document_number', $st_number)
			    ->join('reason', 'pos_pull.reason_id', '=', 'reason.id')
				->leftJoin('transport_types', 'pos_pull.transport_types_id', '=', 'transport_types.id')
				->leftJoin('cms_users', 'pos_pull.scheduled_by', '=', 'cms_users.id')
				->where('pos_pull.st_document_number', $st_number)
				->select('pos_pull.*','reason.pullout_reason','transport_types.transport_type','cms_users.name as scheduled_by')
				->get();

			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id)->first();

			$data['transfer_to'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id_destination)->first();

			$items = DB::table('pos_pull')
			->where('st_document_number',$st_number)
			->select('id','item_code','quantity')
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
					'item_description' => $item_detail->item_description,
					'price' => $item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;

			$this->cbView("stock-transfer.print", $data);
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
				->leftJoin('cms_users as cu1', 'pos_pull.approved_by', '=', 'cu1.id')
				->leftJoin('cms_users as cu2', 'pos_pull.rejected_by', '=', 'cu2.id')
				->where('st_document_number', $st_number)
				->select('pos_pull.*','reason.pullout_reason','transport_types.transport_type','cu1.name as approver','cu2.name as rejector')
				->get();
			
			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id)->first();

			$data['transfer_to'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id_destination)->first();

			$items = DB::table('pos_pull')
				->where('st_document_number',$st_number)
				->select('id','item_code','quantity')
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
					'item_description' => $item_detail->item_description,
					'price' => $item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;
			
			$this->cbView("stock-transfer.detail", $data);
		}

		public function exportSTS(Request $request)
		{
			$store = DB::table('stores')->where('id', CRUDBooster::myStore())->first();
			ini_set('memory_limit', '-1');

			$sts_item = DB::table('pos_pull')->select(
				'pos_pull.st_document_number as st_number',
				'pos_pull.received_st_number',
				'reason.pullout_reason',
				'pos_pull.item_code as digits_code',
				'items.upc_code',
				'items.item_description',
				'stores.bea_so_store_name as source',
				'stores1.bea_so_store_name as destination',
				'pos_pull.quantity as sts_quantity',
				'transport_types.transport_type as transport_by',
				'pos_pull.scheduled_at',
				'scheduled_log.name as scheduled_by',
				'pos_pull.created_at as created_date',
				'pos_pull.received_st_date as received_date',
				'pos_pull.status')
			->leftJoin('items', 'pos_pull.item_code', '=', 'items.digits_code')
			->leftJoin('transport_types', 'pos_pull.transport_types_id', '=', 'transport_types.id')
			->leftJoin('cms_users as scheduled_log', 'pos_pull.scheduled_by', '=', 'scheduled_log.id')
			->leftJoin('reason', 'pos_pull.reason_id', '=', 'reason.id')
			->leftJoin('stores as stores', 'pos_pull.stores_id', '=', 'stores.id')
			->leftJoin('stores as stores1', 'pos_pull.stores_id_destination', '=', 'stores1.id');
			
			if(sizeof($request->filter_column) != 0) {

				$filter_column = $request->filter_column;

				$sts_item->where(function($w) use ($filter_column,$fc) {
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
							$sts_item->orderby($key,$sorting);
							$filter_is_orderby = true;
						}
					}

					if ($type=='between') {
						if($key && $value) $sts_item->whereBetween($key,$value);
					}

					else {
						continue;
					}
				}
			}
			if(!CRUDBooster::isSuperadmin() && !in_array(CRUDBooster::myPrivilegeName(), ["Audit","Inventory Control","Merch"])){
				if (in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
					$sts_item->where('pos_pull.transport_types_id',1);
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

					//compare the store_list of approver to purchase header store_id
					$sts_item->whereIn('pos_pull.stores_id', array_values((array)$storeList));
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Online Ops","Retail Ops","Franchise Ops","Online Viewer"])){
					if(empty($store)){
						$sts_item->where('pos_pull.channel_id', CRUDBooster::myChannel());
					}
					else{
						$sts_item->where('pos_pull.channel_id', CRUDBooster::myChannel())
						->where(function($subquery) use ($store) {
							$subquery->whereIn('pos_pull.stores_id',CRUDBooster::myStore())->orWhere('pos_pull.wh_to',$store->pos_warehouse);
						});
					}
				}
				elseif(CRUDBooster::myPrivilegeName() == "Rtl Fra Ops") {
					$sts_item->whereIn('pos_pull.channel_id', [1,2]);
				}
				elseif(CRUDBooster::myPrivilegeName() == "Reports") {
					$sts_item->whereIn('pos_pull.channel_id', [1,2,4]);
				}
				else{
					
					$sts_item->where(function($subquery) use ($store) {
						$subquery->where('stores.bea_so_store_name',$store->bea_so_store_name)
						->orWhere('stores1.bea_so_store_name',$store->bea_so_store_name);
					});
				}
			}
			$sts_item->orderBy('pos_pull.st_document_number', 'asc');
			$stsItems = $sts_item->get();
			
			$headings = array('ST #',
				'RECEIVED ST #',
				'REASON',
				'DIGITS CODE',
				'UPC CODE',
				'ITEM DESCRIPTION',
				'SOURCE',
				'DESTINATION',
				'QTY',
				'TRANSPORT BY',
				'SCHEDULED DATE/BY',
				'CREATED DATE',
				'RECEIVED DATE',
				'STATUS');

			foreach($stsItems as $item) {

				$items_array[] = array(
					$item->st_number,
					$item->received_st_number,
					$item->pullout_reason,
					$item->digits_code,	
					'="'.$item->upc_code.'"',			
					$item->item_description,	
					$item->source,
					$item->destination,
					$item->sts_quantity,
					$item->transport_by,
					(!empty($item->scheduled_at)) ? $item->scheduled_at.' - '.$item->scheduled_by : '',
					$item->created_date,
					$item->received_date,
					$item->status
				);
			}

			ini_set('max_execution_time', 0);
			Excel::create('Export STS - '.date("Ymd H:i:sa"), function($excel) use ($headings, $items_array){
				$excel->sheet('sts', function($sheet) use ($headings, $items_array){
					// Set auto size for sheet
					$sheet->setAutoSIZE(true);
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

		public function exportSTSSerialized(Request $request)
		{
			$store = DB::table('stores')->where('id', CRUDBooster::myStore())->first();
			ini_set('memory_limit', '-1');

			$sts_item = DB::table('pos_pull')->select(
				'pos_pull.st_document_number as st_number',
				'pos_pull.received_st_number',
				'reason.pullout_reason',
				'pos_pull.item_code as digits_code',
				'items.upc_code',
				'items.item_description',
				'stores.bea_so_store_name as source',
				'stores1.bea_so_store_name as destination',
				'pos_pull.quantity as sts_quantity',
				'serials.serial_number',
				'transport_types.transport_type as transport_by',
				'pos_pull.scheduled_at',
				'scheduled_log.name as scheduled_by',
				'pos_pull.created_at as created_date',
				'pos_pull.received_st_date as received_date',
				'pos_pull.status')
			->leftJoin('items', 'pos_pull.item_code', '=', 'items.digits_code')
			->leftJoin('transport_types', 'pos_pull.transport_types_id', '=', 'transport_types.id')
			->leftJoin('cms_users as scheduled_log', 'pos_pull.scheduled_by', '=', 'scheduled_log.id')
			->leftJoin('reason', 'pos_pull.reason_id', '=', 'reason.id')
			->leftJoin('serials', 'pos_pull.id', '=', 'serials.pos_pull_id')
			->leftJoin('stores', 'pos_pull.stores_id', '=', 'stores.id')
			->leftJoin('stores as stores1', 'pos_pull.stores_id_destination', '=', 'stores1.id');
			
			if(sizeof($request->filter_column) != 0) {

				$filter_column = $request->filter_column;

				$sts_item->where(function($w) use ($filter_column,$fc) {
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
							$sts_item->orderby($key,$sorting);
							$filter_is_orderby = true;
						}
					}

					if ($type=='between') {
						if($key && $value) $sts_item->whereBetween($key,$value);
					}

					else {
						continue;
					}
				}
			}
			if(!CRUDBooster::isSuperadmin() && !in_array(CRUDBooster::myPrivilegeName(), ["Audit","Inventory Control","Merch"])){
				if (in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
					$sts_item->where('pos_pull.transport_types_id',1);
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

					//compare the store_list of approver to purchase header store_id
					$sts_item->whereIn('pos_pull.stores_id', array_values((array)$storeList));
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Online Ops","Retail Ops","Franchise Ops","Online Viewer"])){
					if(empty($store)){
						$sts_item->where('pos_pull.channel_id', CRUDBooster::myChannel());
					}
					else{
						$sts_item->where('pos_pull.channel_id', CRUDBooster::myChannel())
						->where(function($subquery) use ($store) {
							$subquery->whereIn('pos_pull.stores_id',CRUDBooster::myStore())->orWhere('pos_pull.wh_to',$store->pos_warehouse);
						});
					}
				}
				elseif(CRUDBooster::myPrivilegeName() == "Rtl Fra Ops") {
					$sts_item->whereIn('pos_pull.channel_id', [1,2]);
				}
				elseif(CRUDBooster::myPrivilegeName() == "Reports") {
					$sts_item->whereIn('pos_pull.channel_id', [1,2,4]);
				}
				else {
					$sts_item->where(function($subquery) use ($store) {
						$subquery->where('stores.bea_so_store_name',$store->bea_so_store_name)
						->orWhere('stores1.bea_so_store_name',$store->bea_so_store_name);
					});
				}
			}
			$sts_item->orderBy('pos_pull.st_document_number', 'asc');
			$stsItems = $sts_item->get();
			
			$headings = array('ST #',
				'RECEIVED ST #',
				'REASON',
				'DIGITS CODE',
				'UPC CODE',
				'ITEM DESCRIPTION',
				'SOURCE',
				'DESTINATION',
				'QTY',
				'SERIAL #',
				'TRANSPORT BY',
				'SCHEDULED DATE/BY',
				'CREATED DATE',
				'RECEIVED DATE',
				'STATUS');

			foreach($stsItems as $item) {

				$items_array[] = array(
					$item->st_number,
					$item->received_st_number,
					$item->pullout_reason,
					$item->digits_code,	
					'="'.$item->upc_code.'"',			
					$item->item_description,	
					$item->source,
					$item->destination,
					(empty($item->serial_number)) ? $item->sts_quantity : 1,
					$item->serial_number,
					$item->transport_by,
					(!empty($item->scheduled_at)) ? $item->scheduled_at.' - '.$item->scheduled_by : '',
					$item->created_date,
					$item->received_date,
					$item->status
				);
			}

			ini_set('max_execution_time', 0);
			Excel::create('Export STS with Serial- '.date("Ymd H:i:sa"), function($excel) use ($headings,$items_array){
				$excel->sheet('sts-serial', function($sheet) use ($headings,$items_array){
					// Set auto size for sheet
					$sheet->setAutoSIZE(true);
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


	}
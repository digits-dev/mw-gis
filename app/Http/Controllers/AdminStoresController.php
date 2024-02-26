<?php namespace App\Http\Controllers;

	use Session;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Input;
	use DB;
	use CRUDBooster;
	use Excel;

	class AdminStoresController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "bea_so_store_name";
			$this->limit = "20";
			$this->orderby = "bea_so_store_name,asc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "stores";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"BEA SO Store Name","name"=>"bea_so_store_name"];
			$this->col[] = ["label"=>"BEA MO Store Name","name"=>"bea_mo_store_name"];
			$this->col[] = ["label"=>"POS Branch Good","name"=>"pos_warehouse_branch"];
			$this->col[] = ["label"=>"POS WH Good","name"=>"pos_warehouse"];
			$this->col[] = ["label"=>"POS Branch Transit","name"=>"pos_warehouse_transit_branch"];
			$this->col[] = ["label"=>"POS WH Transit","name"=>"pos_warehouse_transit"];
			$this->col[] = ["label"=>"POS Branch RMA","name"=>"pos_warehouse_rma_branch"];
			$this->col[] = ["label"=>"POS WH RMA","name"=>"pos_warehouse_rma"];
			$this->col[] = ["label"=>"POS WH Name","name"=>"pos_warehouse_name"];
			$this->col[] = ["label"=>"Channel","name"=>"channel_id","join"=>"channel,channel_description"];
			$this->col[] = ["label"=>"Customer Type","name"=>"customer_type_id","join"=>"customer_type,customer_type_description"];
			$this->col[] = ["label"=>"STS Group","name"=>"sts_group"];
			$this->col[] = ["label"=>"Location","name"=>"locations_id","join"=>"locations,location_description"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'BEA SO Store Name','name'=>'bea_so_store_name','type'=>'text-store','validation'=>'required|min:1|max:100','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'BEA MO Store Name','name'=>'bea_mo_store_name','type'=>'text-store','validation'=>'required|min:1|max:100','width'=>'col-sm-5'];
			
			if(CRUDBooster::isSuperadmin()){
				$this->form[] = ['label'=>'BEA DOO Subinventory','name'=>'doo_subinventory','type'=>'text-store','validation'=>'max:100','width'=>'col-sm-5'];
				$this->form[] = ['label'=>'BEA SIT Subinventory','name'=>'sit_subinventory','type'=>'text-store','validation'=>'max:100','width'=>'col-sm-5'];
				$this->form[] = ['label'=>'BEA DTO/DEE Subinventory','name'=>'org_subinventory','type'=>'text-store','validation'=>'required|min:1|max:100','width'=>'col-sm-5'];
				
			}
			$this->form[] = ['label'=>'POS Branch Good','name'=>'pos_warehouse_branch','type'=>'text-store','validation'=>'required|min:1|max:100','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'POS WH Good','name'=>'pos_warehouse','type'=>'text-store','validation'=>'required|min:1|max:100','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'POS Branch Transit','name'=>'pos_warehouse_transit_branch','type'=>'text-store','validation'=>'max:100','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'POS WH Transit','name'=>'pos_warehouse_transit','type'=>'text-store','validation'=>'max:100','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'POS Branch RMA','name'=>'pos_warehouse_rma_branch','type'=>'text-store','validation'=>'max:100','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'POS WH RMA','name'=>'pos_warehouse_rma','type'=>'text-store','validation'=>'max:100','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'POS WH Name','name'=>'pos_warehouse_name','type'=>'text-store','validation'=>'required|min:1|max:100','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'Channel','name'=>'channel_id','type'=>'select-store','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'channel,channel_description'];
			$this->form[] = ['label'=>'Customer Type','name'=>'customer_type_id','type'=>'select-store','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'customer_type,customer_type_description'];
			$this->form[] = ['label'=>'STS Group','name'=>'sts_group','type'=>'number-store','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'Location','name'=>'locations_id','type'=>'select-store','validation'=>'integer|min:0','width'=>'col-sm-5','datatable'=>'locations,location_description'];
			if(CRUDBooster::getCurrentMethod() == 'getEdit' || CRUDBooster::getCurrentMethod() == 'postEditSave' || CRUDBooster::getCurrentMethod() == 'getDetail') {
			    $this->form[] = ['label'=>'Status','name'=>'status','type'=>'select-store','validation'=>'required','width'=>'col-sm-5','dataenum'=>'ACTIVE;INACTIVE'];
			}# END FORM DO NOT REMOVE THIS LINE

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
			if(CRUDBooster::isUpdate() && CRUDBooster::isSuperadmin())
	        {
				$this->button_selected[] = ['label'=>'Set STS Group 1','icon'=>'fa fa-check-circle','name'=>'set_sts_group_1'];
				$this->button_selected[] = ['label'=>'Set STS Group 2','icon'=>'fa fa-check-circle','name'=>'set_sts_group_2'];
				$this->button_selected[] = ['label'=>'Set STS Group 3','icon'=>'fa fa-check-circle','name'=>'set_sts_group_3'];
				
				$this->button_selected[] = ['label'=>'Set Bulk Receiving','icon'=>'fa fa-check-circle','name'=>'set_bulk_receiving'];
				$this->button_selected[] = ['label'=>'Reset Bulk Receiving','icon'=>'fa fa-times-circle','name'=>'reset_bulk_receiving'];
				
				$this->button_selected[] = ['label'=>'Set Status ACTIVE','icon'=>'fa fa-check-circle','name'=>'set_status_active'];
				$this->button_selected[] = ['label'=>'Set Status INACTIVE','icon'=>'fa fa-times-circle','name'=>'set_status_inactive'];
			}
	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = text-store of message 
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
			if(CRUDBooster::getCurrentMethod() == "getIndex"){
				if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(), ["Admin"])){
					$this->index_button[] = ["title"=>"Upload Stores",
					"label"=>"Upload Stores",
					"color"=>"info",
					"icon"=>"fa fa-upload","url"=>CRUDBooster::mainpath('import')];
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
			if($button_name == 'set_sts_group_1') {
				DB::table('stores')->whereIn('id',$id_selected)->update([
					'sts_group'=>'1', 
					'updated_at' => date('Y-m-d H:i:s'), 
					'updated_by' => CRUDBooster::myId()
				]);
				
			}   
			elseif($button_name == 'set_sts_group_2') {
				DB::table('stores')->whereIn('id',$id_selected)->update([
					'sts_group'=>'2', 
					'updated_at' => date('Y-m-d H:i:s'), 
					'updated_by' => CRUDBooster::myId()
				]);
				
			}
			elseif($button_name == 'set_sts_group_3') {
				DB::table('stores')->whereIn('id',$id_selected)->update([
					'sts_group'=>'3', 
					'updated_at' => date('Y-m-d H:i:s'), 
					'updated_by' => CRUDBooster::myId()
				]);
				
			}
			elseif($button_name == 'set_status_active') {
				DB::table('stores')->whereIn('id',$id_selected)->update([
					'status'=>'ACTIVE', 
					'updated_at' => date('Y-m-d H:i:s'), 
					'updated_by' => CRUDBooster::myId()
				]);
				
			}
			elseif($button_name == 'set_status_inactive') {
				DB::table('stores')->whereIn('id',$id_selected)->update([
					'status'=>'INACTIVE', 
					'updated_at' => date('Y-m-d H:i:s'), 
					'updated_by' => CRUDBooster::myId()
				]);
				
			}
			elseif($button_name == 'set_bulk_receiving') {
				DB::table('stores')->whereIn('id',$id_selected)->update([
					'allowed_bulk_receiving' => 1, 
					'updated_at' => date('Y-m-d H:i:s'), 
					'updated_by' => CRUDBooster::myId()
				]);
				
			}
			elseif($button_name == 'reset_bulk_receiving') {
				DB::table('stores')->whereIn('id',$id_selected)->update([
					'allowed_bulk_receiving' => 0, 
					'updated_at' => date('Y-m-d H:i:s'), 
					'updated_by' => CRUDBooster::myId()
				]);
				
			}
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
			$postdata['created_by']=CRUDBooster::myId();
			$postdata['status']='ACTIVE';
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
			$postdata['updated_by']=CRUDBooster::myId();
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
// 			$postdata['status']='INACTIVE';
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
		
		public function getTransitWarehouse($good_warehouse)
		{
			$transit_wh = DB::table('stores')
				->where('pos_warehouse',$good_warehouse)
				->select('pos_warehouse_transit','pos_warehouse_branch')->first();
			return json_encode($transit_wh);
		}

		public function getRMAWarehouse($good_warehouse)
		{
			$rma_wh = DB::table('stores')
				->where('pos_warehouse',$good_warehouse)
				->select('pos_warehouse_rma','pos_warehouse_branch')->first();
			return json_encode($rma_wh);
		}

		public function getStoreImportTemplate() {
		    $data['page_title']= 'Store Creation Import';
		    
			return view('stores.import', $data)->render();
		}

		public function downloadStoreImportTemplate()
		{
			Excel::create('stores'.date("Ymd").'-'.date("h.i.sa"), function ($excel) {
				$excel->sheet('store', function ($sheet) {
					$sheet->row(1, array(
						'BEA SO STORE NAME', 
						'BEA MO STORE NAME', 
						'BEA DOO SUBINVENTORY',
						'BEA SIT SUBINVENTORY',
						'BEA ORG SUBINVENTORY',
						'POS BRANCH GOOD',
						'POS WH GOOD',
						'POS BRANCH TRANSIT',
						'POS WH TRANSIT',
						'POS BRANCH RMA',
						'POS WH RMA',
						'POS WH NAME',
						'CHANNEL',
						'CUSTOMER TYPE',
						'STATUS'
					));
					$sheet->row(2, array(
						'DIGITAL WALKER.AYALA.TRINOMA.RTL', 
						'DIGITAL WALKER AYALA TRINOMA RTL', 
						'DW',
						'',
						'RETAIL',
						'GDWTRINOMA',
						'GDWTRINOMA',
						'TDWTRINOMA',
						'TDWTRINOMA',
						'RDWTRINOMA',
						'RDWTRINOMA',
						'DW TRINOMA',
						'RETAIL',
						'RETAIL',
						'ACTIVE'
					));
				});
			})->download('csv');
		}

		public function storeImport(Request $request)
		{
			$file = $request->file('import_file');
			$counter = 0;
			$validator = \Validator::make(
				[
					'file' => $file,
					'extension' => strtolower($file->getClientOriginalExtension()),
				],
				[
					'file' => 'required',
					'extension' => 'required|in:csv',
				]
			);

			if ($validator->fails()) {
				CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_import_data_failed"), 'danger');
			}

			if (Input::hasFile('import_file')) {
				$path = Input::file('import_file')->getRealPath();
				
				$excel_datas = array();
				
				$csv = array_map('str_getcsv', file($path));
				$dataExcel = Excel::load($path, function($reader) {
                })->get();
				
				$unMatch = [];
				$header = array(
					'BEA SO STORE NAME', 
					'BEA MO STORE NAME', 
					'BEA DOO SUBINVENTORY',
					'BEA SIT SUBINVENTORY',
					'BEA ORG SUBINVENTORY',
					'POS BRANCH GOOD',
					'POS WH GOOD',
					'POS BRANCH TRANSIT',
					'POS WH TRANSIT',
					'POS BRANCH RMA',
					'POS WH RMA',
					'POS WH NAME',
					'CHANNEL',
					'CUSTOMER TYPE',
					'STATUS'
				);

				for ($i=0; $i < sizeof($csv[0]); $i++) {
					if (! in_array($csv[0][$i], $header)) {
						$unMatch[] = $csv[0][$i];
					}
				}

				if(!empty($unMatch)) {
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_import_data_failed"), 'danger');
				}

				if(!empty($dataExcel)){
					foreach ($dataExcel as $key => $value) {
						$channel_id = DB::table('channel')->where('channel_description', $value->channel)->value('id');
						$customer_type_id = DB::table('customer_type')->where('customer_type_description', $value->customer_type)->value('id');
						$existing = DB::table('stores')->where('bea_so_store_name', $value->bea_so_store_name)->first();
						$record = false;
						if($existing){
							$record = DB::table('stores')->where('bea_so_store_name', $value->bea_so_store_name)->update([
								'bea_so_store_name' => $value->bea_so_store_name,
								'bea_mo_store_name' => $value->bea_mo_store_name,
								'doo_subinventory' => $value->bea_doo_subinventory,
								'sit_subinventory' => $value->bea_sit_subinventory,
								'org_subinventory' => $value->bea_org_subinventory,
								'pos_warehouse_branch' => $value->pos_branch_good,
								'pos_warehouse' => $value->pos_wh_good,
								'pos_warehouse_transit_branch' => $value->pos_branch_transit,
								'pos_warehouse_transit' => $value->pos_wh_transit,
								'pos_warehouse_rma_branch' => $value->pos_branch_rma,
								'pos_warehouse_rma' => $value->pos_wh_rma,
								'pos_warehouse_name' => $value->pos_wh_name,
								'channel_id' => $channel_id,
								'customer_type_id' => $customer_type_id,
								'status' => $value->status,
								'updated_by' => CRUDBooster::myId(),
								'updated_at' => date('Y-m-d H:i:s')
							]);
						}
						else{
							$record = DB::table('stores')->insert([
								'bea_so_store_name' => $value->bea_so_store_name,
								'bea_mo_store_name' => $value->bea_mo_store_name,
								'doo_subinventory' => $value->bea_doo_subinventory,
								'sit_subinventory' => $value->bea_sit_subinventory,
								'org_subinventory' => $value->bea_org_subinventory,
								'pos_warehouse_branch' => $value->pos_branch_good,
								'pos_warehouse' => $value->pos_wh_good,
								'pos_warehouse_transit_branch' => $value->pos_branch_transit,
								'pos_warehouse_transit' => $value->pos_wh_transit,
								'pos_warehouse_rma_branch' => $value->pos_branch_rma,
								'pos_warehouse_rma' => $value->pos_wh_rma,
								'pos_warehouse_name' => $value->pos_wh_name,
								'channel_id' => $channel_id,
								'customer_type_id' => $customer_type_id,
								'status' => $value->status,
								'created_by' => CRUDBooster::myId(),
								'created_at' => date('Y-m-d H:i:s')
							]);
						}
						

						if($record){
							$counter++;
						}

					}
				}

				if($counter == count($dataExcel)){
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_import_data_success",['count'=>$counter]), 'success');
				}
				else{
					CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_import_data_failed"), 'danger');
				}

			}
		}
	}
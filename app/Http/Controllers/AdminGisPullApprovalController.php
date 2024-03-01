<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\ApprovalMatrix;
	use App\GisPull;

	class AdminGisPullApprovalController extends \crocodicstudio\crudbooster\controllers\CBController {
		private const Pending      = 1;
		private const ForSchedule  = 6;
		private const Rejected     = 3;
		private const forReceiving = 2;
	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "gis_pulls";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Ref #","name"=>"ref_number"];
			$this->col[] = ["label"=>"From Location","name"=>"stores_id","join"=>"stores,bea_so_store_name"];
			$this->col[] = ["label"=>"To Location","name"=>"stores_id_destination","join"=>"stores,bea_so_store_name"];
			$this->col[] = ["label"=>"Status","name"=>"status_id","join"=>"st_status,status_description"];
			$this->col[] = ["label"=>"Transport Type","name"=>"transport_types_id","join"=>"transport_types,transport_type"];
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
			if(CRUDBooster::isUpdate()) {
				$this->addaction[] = ['title'=>'Update','url'=>CRUDBooster::mainpath('getRequestApproval/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[status_id] == ".self::Pending."","color"=>"success"];
			}

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
	        $this->alert        = array();
	                

	        
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
			if(CRUDBooster::isSuperadmin()){
				$query->whereNull('gis_pulls.deleted_at')->orderBy('gis_pulls.status_id', 'DESC')->where('gis_pulls.status_id', self::Pending)->orderBy('gis_pulls.id', 'DESC');
			
			}else{
				$approvalMatrix = ApprovalMatrix::where('approval_matrix.cms_users_id', CRUDBooster::myId())->get();
				
				$approval_array = array();
				foreach($approvalMatrix as $matrix){
					array_push($approval_array, $matrix->store_list);
				}
				$approval_string = implode(",",$approval_array);
				$storeList = array_map('intval',explode(",",$approval_string));

				//compare the store_list of approver to purchase header store_id
				$query->whereIn('gis_pulls.stores_id', array_values((array)$storeList))
					->where('gis_pulls.status_id', self::Pending);

			}
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	if($column_index == 3){
				if($column_value == "PENDING"){
					$column_value = '<span class="label label-warning">PENDING</span>';
				}else if($column_value == "FOR PICKLIST"){
					$column_value = '<span class="label label-warning">FOR PICKLIST</span>';
				}else if($column_value == "FOR PICK CONFIRM"){
					$column_value = '<span class="label label-warning">FOR PICK CONFIRM</span>';
				}else if($column_value == "FOR SCHEDULE"){
					$column_value = '<span class="label label-warning">FOR SCHEDULE</span>';
				}else if($column_value == "FOR RECEIVING"){
					$column_value = '<span class="label label-warning">FOR RECEIVING</span>';
				}else if($column_value == "RECEIVED"){
					$column_value = '<span class="label label-success">RECEIVED</span>';
				}else if($column_value == "VOID"){
					$column_value = '<span class="label label-danger">VOID</span>';
				}else if($column_value == "CLOSED"){
					$column_value = '<span class="label label-danger">CLOSED</span>';
				}else if($column_value == "REJECTED"){
					$column_value = '<span class="label label-danger">REJECTED</span>';
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
			$fields = Request::all();
			$header = DB::table('gis_pulls')->where('id',$id)->first();
			$items = DB::table('gis_pull_lines')->where('gis_pull_id',$id)->get();

			if($fields['approval_action']  == 1){
				if($header->transport_types_id == 1){
					$postdata['status_id']      = self::ForSchedule;
				}else{
					$postdata['status_id']      = self::forReceiving;
				}
				$postdata['approver_comments'] 	= $$fields['approver_comments'];
				$postdata['approved_by'] 		= CRUDBooster::myId();
				$postdata['approved_at'] 		= date('Y-m-d H:i:s');
			}else{
				$postdata['status_id']          = self::Rejected;
				$postdata['approver_comments'] 	= $$fields['approver_comments'];
				$postdata['rejected_by'] 		= CRUDBooster::myId();
				$postdata['rejected_at'] 		= date('Y-m-d H:i:s');

				//REVERT QTY IN GIS INVENTORY LINES
				foreach($items as $key => $item){
					DB::connection('gis')->table('inventory_capsules')
					->leftjoin('inventory_capsule_lines','inventory_capsules.id','inventory_capsule_lines.inventory_capsules_id')
					->leftjoin('items','inventory_capsules.item_code','items.digits_code2')
					->where([
						'items.digits_code' => $item->item_code,
						'inventory_capsules.locations_id' => $header->location_id_from
					])
					->where('inventory_capsule_lines.sub_locations_id',$header->sub_location_id_from)
					->update([
						'qty' => DB::raw("qty + $item->quantity"),
						'inventory_capsule_lines.updated_at' => date('Y-m-d H:i:s')
					]);
				}
				
			}
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

		public function getRequestApproval($id){
			if(!CRUDBooster::isRead() && $this->global_privilege == false || $this->button_detail == false) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$this->cbLoader();
			$data = array();
			$data['page_title'] = 'Stock Transfer GIS Details';
			$data['header'] = GisPull::stGisHeader($id);
			$data['items'] = DB::table('gis_pull_lines')->where('gis_pull_id',$id)->get();
			$this->cbView("stock-transfer.gis-st-approval", $data);
		}

		public function getDetail($id){
			if(!CRUDBooster::isRead() && $this->global_privilege == false || $this->button_detail == false) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$this->cbLoader();
			$data = array();
			$data['page_title'] = 'Stock Transfer GIS Details';
			$data['header'] = GisPull::stGisHeader($id);
			$data['items'] = DB::table('gis_pull_lines')->where('gis_pull_id',$id)->get();
			// dd($data['items'],$data['header']);
			$this->cbView("stock-transfer.gis-st-detail", $data);
		}
	}
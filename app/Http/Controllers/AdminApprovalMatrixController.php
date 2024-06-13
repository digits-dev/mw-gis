<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\ApprovalMatrix;

	class AdminApprovalMatrixController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "channel_id,asc";
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
			$this->table = "approval_matrix";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Privilege Name","name"=>"id_cms_privileges","join"=>"cms_privileges,name"];
			$this->col[] = ["label"=>"Approver Name","name"=>"cms_users_id","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Channel","name"=>"channel_id","join"=>"channel,channel_description"];
			$this->col[] = ["label"=>"Store List","name"=>"store_list"];
			$this->col[] = ["label"=>"Viewable Channel","name"=>"channels_visibility","join"=>"channel,channel_description"];
			
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Privilege Name','name'=>'id_cms_privileges','type'=>'select','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'cms_privileges,name','datatable_where'=>"id in (5,28)"];
			$this->form[] = ['label'=>'Approver Name','name'=>'cms_users_id','type'=>'select','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'cms_users,name','parent_select'=>'id_cms_privileges'];
			$this->form[] = ['label'=>'Channel','name'=>'channel_id','type'=>'select','validation'=>'required|integer|min:0','width'=>'col-sm-5','datatable'=>'channel,channel_description'];
			
			if(CRUDBooster::getCurrentMethod() == 'getEdit' || CRUDBooster::getCurrentMethod() == 'postEditSave' || CRUDBooster::getCurrentMethod() == 'getDetail'){
				$this->form[] = ['label'=>'Status','name'=>'status','type'=>'select','validation'=>'required','width'=>'col-sm-5','dataenum'=>'ACTIVE;INACTIVE'];
			}	
			$this->form[] = ['label'=>'Store List','name'=>'store_list','type'=>'check-box','validation'=>'required','width'=>'col-sm-5','datatable'=>'stores,bea_so_store_name','datatable_where'=>"status=%27ACTIVE%27",'parent_select'=>'channel_id'];
			$this->form[] = ['label'=>'Viewable Channel Orders','name'=>'channels_visibility','type'=>'checkbox','width'=>'col-sm-5','dataenum'=>'1|RETAIL;2|FRANCHISE;3|DISTRIBUTION;4|ONLINE'];
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
            if(CRUDBooster::isSuperadmin())
            {
                $this->button_selected[] = ['label'=>'Clone','icon'=>'fa fa-file-text','name'=>'clone'];
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
	        if($button_name == 'clone') {
                $approvalMatrices = DB::table('approval_matrix')->whereIn('id',$id_selected)->get();
                
                foreach($approvalMatrices as $key => $matrix){
                    DB::table('approval_matrix')->insert([
                        'id_cms_privileges' => $matrix->id_cms_privileges,
                        'cms_users_id' => DB::table('cms_users')->orderBy('created_at','desc')->value('id'),
                        'channel_id' => $matrix->channel_id,
                        'store_list' => $matrix->store_list,
                        'channels_visibility' => $matrix->channels_visibility,
                        'status' => $matrix->status,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
                
                
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
			if($column_index == 4){
				$storeLists = $this->storeListing($column_value);
				
				foreach ($storeLists as $value) {
					$col_values .= '<span stye="display: block;" class="label label-info">'.$value.'</span><br>';
				}
				$column_value = $col_values;
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
			$storeData = array();
			$storeList = json_encode($postdata['store_list'], true);
			$storeArray = explode(",", $storeList);

			foreach ($storeArray as $key => $value) {
				$storeData[$key] = preg_replace("/[^0-9]/","",$value);
			}
			

			$postdata['store_list'] = implode(",", $storeData);
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
			$storeData = array();
			$storeList = json_encode($postdata['store_list'], true);
			$storeArray = explode(",", $storeList);
			
			foreach ($storeArray as $key => $value) {
				$storeData[$key] = preg_replace("/[^0-9]/","",$value);
			}

			$postdata['store_list'] = implode(",", $storeData);
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
		
		public function storeListing($ids) {
			$stores = explode(",", $ids);
			return DB::table('stores')->whereIn('id', $stores)->pluck('bea_so_store_name');
		}

		public function getDetail($id) {
			if(!CRUDBooster::isRead() && $this->global_privilege == false || $this->button_detail == false) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data = [];
			$data['page_title'] = 'Approval Matrix Details';
			$data['approval_details'] = ApprovalMatrix::where('approval_matrix.id',$id)
								->join('cms_privileges', 'approval_matrix.id_cms_privileges', '=', 'cms_privileges.id')
								->join('cms_users', 'approval_matrix.cms_users_id', '=', 'cms_users.id')
								->join('channels', 'approval_matrix.channel_id', '=', 'channels.id')
								->select('approval_matrix.*','cms_privileges.name as privilege_name','cms_users.name as cms_user_name','channels.*')
								->first();
								
			$data['store_lists'] = $this->storeListing($data['approval_details']['store_list']);
			$this->cbView("approval-matrix.approval_detail", $data);
		}


	}
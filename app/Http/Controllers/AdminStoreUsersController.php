<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminStoreUsersController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			// $this->limit = "20";
			// $this->orderby = "id,desc";
			// $this->button_table_action = true;
			// $this->button_bulk_action = true;
			// $this->button_add = true;
			// $this->button_edit = true;
			// $this->button_detail = true;
			// $this->button_show = true;
			// $this->button_filter = true;
			
			$this->global_privilege = false;
			$this->table               = 'cms_users';
			$this->primary_key         = 'id';
			$this->title_field         = "name";
			$this->button_action_style = 'button_icon';	
			$this->button_import 	   = false;	
			$this->button_export 	   = false;	
			$this->button_delete = false;

			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Name","name"=>"name"];
			$this->col[] = ["label"=>"Email","name"=>"email"];
			$this->col[] = ["label"=>"Privileges","name"=>"id_cms_privileges","join"=>"cms_privileges,name"];
			$this->col[] = ["label"=>"Channel","name"=>"channel_id","join"=>"channel,channel_description"];
			$this->col[] = ["label"=>"Store","name"=>"stores_id"];
			$this->col[] = ["label"=>"Photo","name"=>"photo","image"=>true];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|min:3','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Email','name'=>'email','type'=>'email','validation'=>'required|email|unique:cms_users,email,60','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Photo','name'=>'photo','type'=>'upload','validation'=>'image|max:1000','width'=>'col-sm-10','help'=>'Recommended resolution is 200x200px'];
			$this->form[] = ['label'=>'Privilege','name'=>'id_cms_privileges','type'=>'select','width'=>'col-sm-10','datatable'=>'cms_privileges,name'];
			$this->form[] = ["label"=>"Channel","name"=>"channel_id","type"=>"select2","datatable"=>"channel,channel_description",'datatable_where'=>"status='ACTIVE'",'required'=>CRUDBooster::isSuperadmin() ? false : true];
			$this->form[] = ["label"=>"Store","name"=>"stores_id","type"=>"check-box","datatable"=>"stores,bea_so_store_name","datatable_where"=>"status='ACTIVE'",  "parent_select"=>"channel_id", 'required'=>CRUDBooster::isSuperadmin() ? false : true];						
			$this->form[] = ['label'=>'Password','name'=>'password','type'=>'password','validation'=>'min:3|max:32','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|min:3','readonly'=>(CRUDBooster::isSuperadmin()) ? false : true];
			//$this->form[] = ['label'=>'Email','name'=>'email','type'=>'email','validation'=>'required|email|unique:cms_users,email,'.CRUDBooster::getCurrentId(),'readonly'=>(CRUDBooster::isSuperadmin()) ? false : true];
			//$this->form[] = ['label'=>'Photo','name'=>'photo','type'=>'upload',"help"=>"Recommended resolution is 200x200px",'validation'=>'image|max:1000'];
			//$this->form[] = ['label'=>'Privilege','name'=>'id_cms_privileges',"type"=>"select","datatable"=>"cms_privileges,name",'required'=>true];
			//$this->form[] = ['label'=>'Channel','name'=>'channel_id','type'=>'select2',"datatable"=>"channel,channel_description",'datatable_where'=>"status='ACTIVE'",'required'=>CRUDBooster::isSuperadmin() ? false : true];
			//$this->form[] = ['label'=>'Stores','name'=>'stores_id','type'=>'radio',"datatable"=>"stores,bea_so_store_name",'datatable_where'=>"status LIKE '%ACTIVE%'", 'required'=>CRUDBooster::isSuperadmin() ? false : true,'parent_select'=>'channel_id'];
			//$this->form[] = ['label'=>'Password','name'=>'password','type'=>'password','validation'=>'min:3|max:32','width'=>'col-sm-10'];
			//
			//
			//
			//// 	$this->form = array();
			//// $this->form[] = array("label"=>"Name","name"=>"name",'required'=>true,'validation'=>'required|min:3','readonly'=>(CRUDBooster::isSuperadmin()) ? false : true);
			//// $this->form[] = array("label"=>"Email","name"=>"email",'required'=>true,'type'=>'email','validation'=>'required|email|unique:cms_users,email,'.CRUDBooster::getCurrentId(),'readonly'=>(CRUDBooster::isSuperadmin()) ? false : true);
			//// $this->form[] = array("label"=>"Photo","name"=>"photo","type"=>"upload","help"=>"Recommended resolution is 200x200px",'validation'=>'image|max:1000');//'required'=>CRUDBooster::isSuperadmin()?false:true
			//// $this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name",'required'=>true);
			//// if(CRUDBooster::isSuperadmin()){
			//// 	$this->form[] = array("label"=>"Channel","name"=>"channel_id","type"=>"select2","datatable"=>"channel,channel_description",'datatable_where'=>"status='ACTIVE'",'required'=>CRUDBooster::isSuperadmin() ? false : true);
			//// 	$this->form[] = array("label"=>"Store","name"=>"stores_id","type"=>"check-box","datatable"=>"stores,bea_so_store_name",'datatable_where'=>"status=%27ACTIVE%27",'required'=>CRUDBooster::isSuperadmin() ? false : true, 'parent_select'=>'channel_id');
			//// }
			//// $this->form[] = array("label"=>"Password","name"=>"password","type"=>"password","help"=>"Please leave empty if not changed");
			# OLD END FORM

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

			if(CRUDBooster::isUpdate()) {
				$this->button_selected[] = ["label"=>"Set Status ACTIVE ","icon"=>"fa fa-check-circle","name"=>"set_status_ACTIVE"];
				$this->button_selected[] = ["label"=>"Set Status INACTIVE","icon"=>"fa fa-times-circle","name"=>"set_status_INACTIVE"];
				$this->button_selected[] = ["label"=>"Reset Password","icon"=>"fa fa-refresh","name"=>"reset_password"];
			}

	                
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
			switch ($button_name) {
				case 'set_status_ACTIVE':
					DB::table('cms_users')->whereIn('id',$id_selected)->update([
						'status'=>'ACTIVE', 
						'updated_at' => date('Y-m-d H:i:s')
					]);
					break;
				case 'set_status_INACTIVE':
					DB::table('cms_users')->whereIn('id',$id_selected)->update([
						'status'=>'INACTIVE', 
						'updated_at' => date('Y-m-d H:i:s')
					]);
					break;
				case 'reset_password':
					DB::table('cms_users')->whereIn('id',$id_selected)->update([
						'password'=>bcrypt('qwerty'),
						'updated_at' => date('Y-m-d H:i:s')
					]);
					break;
				default:
					# code...
					break;
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
			$query->whereNotNull('stores_id')->where('id_cms_privileges', '<>', 1);
			
	            
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
				$storeLists = $this->storeListing($column_value);
				
				foreach ($storeLists as $value) {
					$col_values .= '<span stye="display: block;" class="label label-info">'.$value.'</span><br>';
				}
				$column_value = $col_values;
			}

			if($column_index == 7){
				if($column_value == 'ACTIVE'){
					$col_values = '<span stye="display: block;" class="label label-success">'.$column_value.'</span><br>';
				} else{
					$col_values = '<span stye="display: block;" class="label label-danger">'.$column_value.'</span><br>';
				}
				$column_value = $col_values;
			}
		}
	
		public static function storeListing($ids) {
			$stores = explode(",", $ids);
			return DB::table('stores')->whereIn('id', $stores)->pluck('bea_so_store_name');
		}

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {    
			
			
			if($postdata['photo'] == '' || $postdata['photo'] == NULL) {
				$postdata['photo'] = 'uploads/mrs-avatar.png';
			}
			
			$postdata['status'] = 'ACTIVE';

			// dd($postdata);

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


		public function getAdd() {
			//Create an Auth
			if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {    
			  CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data = [];
			$data['page_title'] = 'Add Store User';
			$data['privileges'] = DB::table('cms_privileges')->whereIn('id', [28,29,30,31,32])->get();
			$data['channels'] = DB::table('channel')->whereIn('id', [1,2])->get();
			$data['stores'] = DB::table('stores')->whereIn('channel_id',[1,2])->get();
			
			//Please use view method instead view method from laravel
			return $this->cbView('users.users_add',$data);
		}

		public function getDetail($id) {
			//Create an Auth
			if(!CRUDBooster::isRead() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {    
			  CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data = [];
			$data['page_title'] = 'Detail Store User';
			$data['row'] = DB::table('cms_users as user')
						  ->join('cms_privileges as privilege', 'privilege.id', 'user.id_cms_privileges')
						  ->join('channel', 'channel.id', 'user.channel_id')
						  ->join('stores', 'stores.id', 'user.stores_id')
						  ->select([
							'user.name',
							'user.email',
							'user.photo',
							'privilege.name as privilege',
							'channel.channel_description',
							'stores.bea_so_store_name',
						  ])
						  ->where('user.id',$id)->first();
			
			//Please use cbView method instead view method from laravel
			$this->cbView('users.users_view',$data);
		}

		  public function getEdit($id) {
			//Create an Auth
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {    
			  CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data = [];
			$data['page_title'] = 'Edit User';
			$data['row'] = DB::table('cms_users')->where('id',$id)->first();
			$data['privileges'] = DB::table('cms_privileges')->whereIn('id', [28,29,30,31,32])->get();
			$data['channels'] = DB::table('channel')->whereIn('id', [1,2])->get();
			$data['stores'] = DB::table('stores')->whereIn('channel_id',[1,2])->get();



			
			$this->cbView('users.users_edit',$data);
		  }

	    //By the way, you can still create your own method in here... :) 


	}
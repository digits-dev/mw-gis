<?php namespace App\Http\Controllers;

	use Session;
	use DB;
	use CRUDBooster;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\File;
	use Illuminate\Http\Request;
	use App\ApprovalMatrix;
	use App\Pullout;
	use App\CodeCounter;

	class AdminPulloutController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct()
		{
			// Apply the middleware to a specific method
			$this->middleware('checkAccessTime')->only('getSTW','getRMA');
		}
	
	    public function cbInit() {
	    	# START CONFIGURATION DO NOT REMOVE THIS LINE

			$this->title_field = "st_document_number";
			$this->limit = "20";
			$this->orderby = "status,asc,created_date,desc";
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
			$this->table = "pullout";

			# END CONFIGURATION DO NOT REMOVE THIS LINE						      

			# START COLUMNS DO NOT REMOVE THIS LINE
	        $this->col = [];
			$this->col[] = ["label"=>"ST/REF #","name"=>"st_document_number"];
			$this->col[] = ["label"=>"Received ST #","name"=>"received_st_number"];
			$this->col[] = ["label"=>"MOR/SOR #","name"=>"sor_number"];
			$this->col[] = ["label"=>"From WH","name"=>"stores_id","join"=>"stores,pos_warehouse_name"];
			$this->col[] = ["label"=>"To WH","name"=>"wh_to"];
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
					'icon'=>'fa fa-times','color'=>'danger',
					'showIf'=>"[status]=='PENDING'",
					'confirmation'=>'yes',
					'confirmation_title'=>'Confirm Voiding',
					'confirmation_text'=>'Are you sure to VOID this transaction?'];
			}	
			if(CRUDBooster::isSuperadmin()){
				$this->addaction[] = [
					'title'=>'Re-run Create ST',
					'url'=>CRUDBooster::mainpath('create-st'),
					'icon'=>'fa fa-refresh',
					'color'=>'success',
					'showIf'=>"[st_document_number]==''",
					'confirmation'=>'yes',
					'confirmation_title'=>'Confirm Re-run ST Creation',
					'confirmation_text'=>'Are you sure to re-run ST creation?'];
				
				$this->addaction[] = [
					'title'=>'Re-run Receive ST',
					'url'=>CRUDBooster::mainpath('received-st').'/[st_document_number]',
					'icon'=>'fa fa-refresh',
					'color'=>'info',
					'showIf'=>"[received_st_number]=='' and [status]!='VOID'",
					'confirmation'=>'yes',
					'confirmation_title'=>'Confirm Re-run ST Receiving',
					'confirmation_text'=>'Are you sure to re-run ST receiving?'];
			}
			if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(),["Requestor","Online Requestor","Online Requestor II"])){
			    $this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('print').'/[st_document_number]','icon'=>'fa fa-print','color'=>'info','showIf'=>"[sor_number]!=''"]; //,'showIf'=>"[status]=='FOR SCHEDULE' and [sor_number]!=''"
			}
			if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])){
				$this->addaction[] = ['title'=>'Schedule','url'=>CRUDBooster::mainpath('schedule').'/[st_document_number]','icon'=>'fa fa-calendar','color'=>'warning','showIf'=>"[status]=='FOR SCHEDULE' and [sor_number]!=''"];
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
			if(CRUDBooster::getCurrentMethod() == 'getIndex' && !in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL","Approver","Warehouse","Gashapon Requestor", "Operations Manager", "Area Manager"])){
				
				if(CRUDBooster::myChannel() != 4){
					$this->index_button[] = ['label'=>'Create STW','url'=>CRUDBooster::mainpath().'/stw/create','icon'=>'fa fa-plus','color'=>'success'];
					$this->index_button[] = ['label'=>'Create ST RMA','url'=>CRUDBooster::mainpath().'/rma/create','icon'=>'fa fa-plus','color'=>'success'];
					
				}
				else{
					$this->index_button[] = ['label'=>'Create STW','url'=>CRUDBooster::mainpath().'/stw-online/create','icon'=>'fa fa-plus','color'=>'success'];
					$this->index_button[] = ['label'=>'Create ST RMA','url'=>CRUDBooster::mainpath().'/rma-online/create','icon'=>'fa fa-plus','color'=>'success'];
					
				}
			}
			if(CRUDBooster::getCurrentMethod() == 'getIndex' && in_array(CRUDBooster::myPrivilegeId(),[27])){
				$this->index_button[] = ['label'=>'Create STW','url'=>CRUDBooster::mainpath().'/stw-gis/create','icon'=>'fa fa-plus','color'=>'success'];
				$this->index_button[] = ['label'=>'Create ST RMA','url'=>CRUDBooster::mainpath().'/rma-gis/create','icon'=>'fa fa-plus','color'=>'success'];
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
				$store = DB::table('stores')->whereIn('id', CRUDBooster::myStore())->get();
				
				if (in_array(CRUDBooster::myPrivilegeName(), ["LOG TM","LOG TL"])) {
					$query->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status')
						->where('transport_types_id',1)->where('pullout.status','FOR SCHEDULE')
						->distinct();
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
	
					$query->whereIn('pullout.stores_id', array_values((array)$storeList))
						->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status')
						->distinct();
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Warehouse","RMA"])){
					$query->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status','pullout.created_date')
					->where('wh_to',$store->pos_warehouse)->distinct();
				}
				else{
					$query->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status','pullout.created_date')
					->whereIn('pullout.stores_id', CRUDBooster::myStore())->distinct();
				}
			}
			else{
				$query->select('pullout.st_document_number','pullout.wh_from','pullout.wh_to','pullout.status','pullout.created_date')
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
			if($column_index == 6){
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
				else if($column_value == "FOR PROCESSING"){
					$column_value = '<span class="label label-default">FOR PROCESSING</span>';
				}
				else if($column_value == "RECEIVED"){
					$column_value = '<span class="label label-success">RECEIVED</span>';
				}
				else if($column_value == "PARTIALLY RECEIVED"){
					$column_value = '<span class="label label-success">PARTIALLY RECEIVED</span>';
				}
				else if($column_value == "VOID"){
					$column_value = '<span class="label label-danger">VOID</span>';
				}
				else if($column_value == "CLOSED"){
					$column_value = '<span class="label label-danger">CLOSED</span>';
				}
			}
			if($column_index == 7){
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

		public function getRMA()
		{
			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'Create ST RMA';

			if(CRUDBooster::isSuperadmin()){
				$data['transfer_from'] = DB::table('stores')
				->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name','doo_subinventory as subinventory')->get();
			}
			else{
				$data['transfer_from'] = DB::table('stores')
				->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name','doo_subinventory as subinventory')
				->whereIn('id',CRUDBooster::myStore())->get();
			}
			

			$data['transfer_to'] = DB::table('stores')
				->select('pos_warehouse','pos_warehouse_name')
				->whereNotIn('id',CRUDBooster::myStore())
				->where('pos_warehouse','DIGITSRMA')
				->where('status','ACTIVE')
				->get();

			$data['transport_types'] = DB::table('transport_types')
				->select('id','transport_type')
				->where('status','ACTIVE')
				->get();
				
		    $data['problems'] = DB::table('problems')
				->select('id','problem_details')
				->where('status','ACTIVE')
				->get();
			
			if(CRUDBooster::myChannel() ==1){ //retail
				$data['reasons'] = DB::table('reason')
				->select('bea_mo_reason as bea_reason','pullout_reason','allow_multi_items')
				->where('transaction_type_id',2) //rma
				->where('status','ACTIVE')
				->get();
			}
			else{
				$data['reasons'] = DB::table('reason')
				->select('bea_so_reason as bea_reason','pullout_reason','allow_multi_items')
				->where('transaction_type_id',2) //rma
				->where('status','ACTIVE')
				->get();
			}
			
			
			$data['transfer_org'] = 225;
			if(in_array(CRUDBooster::myChannel(),[6,7,10,11])) { //con,out
				$this->cbView("pullout.rma-distri-scan", $data);
			}
			else{
				$this->cbView("pullout.rma-scan", $data);
			}
		}

		public function getSTW()
		{
			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'Create STW';

			if(CRUDBooster::isSuperadmin()){
				$data['transfer_from'] = DB::table('stores')
				->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name','doo_subinventory as subinventory')->get();
			}
			else{
				$data['transfer_from'] = DB::table('stores')
				->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name','doo_subinventory as subinventory')
				->whereIn('id',CRUDBooster::myStore())->get();
			}
			

			$data['transfer_to'] = DB::table('stores')
				->select('pos_warehouse','pos_warehouse_name')
				->whereNotIn('id',CRUDBooster::myStore())
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
			
			if(in_array(CRUDBooster::myChannel(),[6,7,10,11])){ //con,out,crp,dlr
			    $this->cbView("pullout.stw-distri-scan", $data);
			}
			else{
			    $this->cbView("pullout.stw-scan", $data);
			}
			
		}

		public function getRMAOnline()
		{
			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'Create ST RMA';

			if(CRUDBooster::isSuperadmin()){
				$data['transfer_from'] = DB::table('stores')
				->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name')->get();
			}
			else{
				$data['transfer_from'] = DB::table('stores')
				->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name')
				->whereIn('id',CRUDBooster::myStore())->get();
			}
			

			$data['transfer_to'] = DB::table('stores')
				->select('pos_warehouse','pos_warehouse_name')
				->whereNotIn('id',CRUDBooster::myStore())
				->where('pos_warehouse','DIGITSRMA')
				->where('status','ACTIVE')
				->get();

			$data['transport_types'] = DB::table('transport_types')
				->select('id','transport_type')
				// ->where('transport_type','Logistics')
				->where('status','ACTIVE')
				->get();
				
			$data['problems'] = DB::table('problems')
				->select('id','problem_details')
				->where('status','ACTIVE')
				->get();
			
			$data['reasons'] = DB::table('reason')
				->select('bea_mo_reason as bea_reason','pullout_reason')
				->where('transaction_type_id',2) //rma
				->where('status','ACTIVE')
				->get();
			
			$data['transfer_org'] = 225;
			if(CRUDBooster::myPrivilegeName() == "Online Requestor II"){
				$this->cbView("pullout.rma-online-fbd-scan", $data);
			}
			else{	
				$this->cbView("pullout.rma-online-scan", $data);
			}
		}

		public function getSTWOnline()
		{
			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'Create STW';

			if(CRUDBooster::isSuperadmin()){
				$data['transfer_from'] = DB::table('stores')
				->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name')->get();
			}
			else{
				$data['transfer_from'] = DB::table('stores')
				->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name')
				->whereIn('id',CRUDBooster::myStore())->get();
			}
			

			$data['transfer_to'] = DB::table('stores')
				->select('pos_warehouse','pos_warehouse_name')
				->whereNotIn('id',CRUDBooster::myStore())
				->where('pos_warehouse','DIGITSWAREHOUSE')
				->where('status','ACTIVE')
				->get();

			$data['transport_types'] = DB::table('transport_types')
				->select('id','transport_type')
				// ->where('transport_type','Logistics')
				->where('status','ACTIVE')
				->get();

			$data['reasons'] = DB::table('reason')
				->select('bea_mo_reason as bea_reason','pullout_reason')
				->where('transaction_type_id',1) //STW
				->where('status','ACTIVE')
				->get();
			

			$data['transfer_org'] = 263;
			if(CRUDBooster::myPrivilegeName() == "Online Requestor II"){
				$this->cbView("pullout.stw-online-fbd-scan", $data);
			}
			else{
				$this->cbView("pullout.stw-online-scan", $data);
			}
			
			
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
				->whereIn('id',CRUDBooster::myStore())->get();
			}
			

			$data['transfer_to'] = DB::table('stores')
				->select('pos_warehouse','pos_warehouse_name')
				->whereNotIn('id',CRUDBooster::myStore())
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
				'pullout_date' => 'required',
				'reason' => 'required',
			],
			[
				'transport_type.required' => 'You have to choose transport by.',
				'digits_code.required' => 'You have to add pullout items.',
				'transfer_to.required' => 'You have to choose pullout to store/warehouse.',
				'pullout_date.required' => 'You have to set date for pullout.',
				'reason.required' => 'You have to choose pullout reason.',
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

			$refcode = 'BEAPOSMW'.date('His');
			$postedST = app(POSPushController::class)->posCreateStockTransfer($refcode, $request->transfer_branch, $request->transfer_from, $request->transfer_transit, $request->memo, $posItemDetails);
			\Log::info('create STW: '.json_encode($postedST));

			$st_number = $postedST['data']['record']['fdocument_no'];
			$record=false;

			if(empty($st_number)){
				//back to old form
				CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No pullout has been created. '.$postedST['data']['record']['errors']['error'] ,'danger')->send();
			}

			$sor_number_stw = NULL;
			// if(CRUDBooster::myChannel() == 1 || (CRUDBooster::myChannel() == 2 && substr($value_item,0,1) == '7')) {
			// 	$sor_number_stw = $st_number;
			// }
			if(CRUDBooster::myChannel() == 4) {
				$sor_number_stw = 'SIT'.$st_number;
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
						'stores_id' => (int)(implode("",CRUDBooster::myStore())),
						'channel_id' => CRUDBooster::myChannel(),
						'memo' => $request->memo,
						'pullout_date' => $request->pullout_date,
						'reason_id' => $request->reason,
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'transaction_type' => 'STW',
						'st_document_number' => $st_number,
						'sor_number' => $sor_number_stw,
						'st_status_id' => 2,
						'has_serial' => 1,
						'status' => 'PENDING',
						'step' => 2,
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
						'stores_id' => (int)(implode("",CRUDBooster::myStore())),
						'channel_id' => CRUDBooster::myChannel(),
						'memo' => $request->memo,
						'pullout_date' => $request->pullout_date,
						'reason_id' => $request->reason,
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'transaction_type' => 'STW',
						'st_document_number' => $st_number,
						'sor_number' => $sor_number_stw,
						'st_status_id' => 2,
						'status' => 'PENDING',
						'step' => 2,
						'created_date' => date('Y-m-d'),
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

			if($record && !empty($st_number)){
			    CRUDBooster::insertLog(trans("crudbooster.stw_created", ['ref_number' =>$st_number]));
                CRUDBooster::redirect(CRUDBooster::mainpath('print').'/'.$st_number,'','')->send();
			}
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
						'stores_id' => (int)(implode("",CRUDBooster::myStore())),
						'channel_id' => CRUDBooster::myChannel(),
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
						'stores_id' => (int)(implode("",CRUDBooster::myStore())),
						'channel_id' => CRUDBooster::myChannel(),
						'memo' => $request->memo,
						'reason_id' => $request->reason,
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'transaction_type' => 'STW Marketing',
						'st_document_number' => $st_number,
						'sor_number' => (in_array(CRUDBooster::myChannel(),[1,4]) || (CRUDBooster::myChannel() == 2 && substr($value_item,0,1) == '7')) ? $st_number: NULL,
						'st_status_id' => 2,
						'status' => (is_null($request->hand_carrier)) ? 'FOR SCHEDULE' : 'FOR RECEIVING',
						'created_date' => date('Y-m-d'),
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
				'pullout_date' => 'required',
				'reason' => 'required',
				// 'problems' => 'required',
			],
			[
				'transport_type.required' => 'You have to choose transport by.',
				'digits_code.required' => 'You have to add pullout items.',
				'transfer_to.required' => 'You have to choose pullout to store/warehouse.',
				'pullout_date.required' => 'You have to set date for pullout.',
				'reason.required' => 'You have to choose pullout reason.',
				// 'problems.required' => 'You have to choose item problem.',
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

			$refcode = 'BEAPOSMW'.date('His');
			$postedST = app(POSPushController::class)->posCreateStockTransfer($refcode, $request->transfer_branch, $request->transfer_from, $request->transfer_rma, $request->memo, $posItemDetails);
			\Log::info('create STR: '.json_encode($postedST));
			$st_number = $postedST['data']['record']['fdocument_no'];
			$record=false;

			if(empty($st_number)){
				//back to old form
				CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No pullout has been created. '.$postedST['data']['record']['errors']['error'] ,'danger')->send();
			}
			//save ST
			foreach ($request->digits_code as $key_item => $value_item) {
				$serial = $value_item.'_serial_number';
				$item_problems = $value_item.'problems';
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
						'stores_id' => (int)(implode("",CRUDBooster::myStore())),
						'channel_id' => CRUDBooster::myChannel(),
						'memo' => $request->memo,
						'pullout_date' => $request->pullout_date,
						'reason_id' => $request->reason,
				// 		'purchase_date' => $request->purchase_date[$key_item],
						'problems' => implode(",",$request->$item_problems),
						'problem_detail' => $request->problem_detail[$key_item],
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'transaction_type' => 'RMA',
						'st_document_number' => $st_number,
						'sor_number' => NULL, 
						'st_status_id' => 2,
						'has_serial' => 1,
						'status' => 'PENDING', 
						'step' => 2,
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
						'stores_id' => (int)(implode("",CRUDBooster::myStore())),
						'channel_id' => CRUDBooster::myChannel(),
						'memo' => $request->memo,
						'pullout_date' => $request->pullout_date,
						'reason_id' => $request->reason,
				// 		'purchase_date' => $request->purchase_date[$key_item],
						'problems' => implode(",",$request->$item_problems),
						'problem_detail' => $request->problem_detail[$key_item],
						'transaction_type' => 'RMA',
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'st_document_number' => $st_number,
						'sor_number' => NULL, 
						'st_status_id' => 2,
						'status' => 'PENDING', 
						'step' => 2,
						'created_date' => date('Y-m-d'),
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
			
			if($record && !empty($st_number)){
			    CRUDBooster::insertLog(trans("crudbooster.str_created", ['ref_number' =>$st_number]));
                CRUDBooster::redirect(CRUDBooster::mainpath('print').'/'.$st_number,'','')->send();
			}
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No pullout has been created','danger')->send();
            }
		}

		public function saveCreateMarketingRMA(Request $request)
		{
			$validator = \Validator::make($request->all(), [
				
				'digits_code' => 'required',
				'transfer_from' => 'required',
				'transfer_to' => 'required',
				'reason' => 'required',
			],
			[
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
			$posItemDetails = array();
			$record = false;

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

			$refcode = 'BEAPOSMW'.date('His');
			$postedST = app(POSPushController::class)->posCreateStockTransfer($refcode, $request->transfer_branch, $request->transfer_from, $request->transfer_rma, $request->memo, $posItemDetails);
			$st_number = $postedST['data']['record']['fdocument_no'];

			if(empty($st_number)){
				//back to old form
				CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No pullout has been created. '.$postedST['data']['record']['errors']['error'] ,'danger')->send();
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
						'stores_id' => (int)(implode("",CRUDBooster::myStore())),
						'channel_id' => CRUDBooster::myChannel(),
						'memo' => $request->memo,
						'reason_id' => $request->reason,
						// 'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'transaction_type' => 'RMA',
						'st_document_number' => $st_number,
						'sor_number' => NULL,
						'st_status_id' => 2,
						'has_serial' => 1,
						'status' => 'PENDING',
						'step' => 2,
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
						'stores_id' => (int)(implode("",CRUDBooster::myStore())),
						'channel_id' => CRUDBooster::myChannel(),
						'memo' => $request->memo,
						'reason_id' => $request->reason,
						'transaction_type' => 'RMA',
						// 'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'st_document_number' => $st_number,
						'sor_number' => NULL, 
						'st_status_id' => 2,
						'status' => 'PENDING', 
						'step' => 2,
						'created_date' => date('Y-m-d'),
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

		public function saveSchedule(Request $request)
		{
			$store = DB::table('stores')->where('pos_warehouse',$request->transfer_from)->first();
			
			$record = DB::table('pullout')
				->where('st_document_number',$request->st_number)
				->update([
					'schedule_date' => $request->schedule_date,
					'schedule_by' => CRUDBooster::myId(),
					'status' => 'FOR RECEIVING',
					'step' => 5,
				]);

			if($record)
                CRUDBooster::redirect(CRUDBooster::mainpath('print').'/'.$request->st_number,'','')->send();
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No transaction has been scheduled for pullout.','danger')->send();
			}
			
		}
		
		public function saveCreateSTWDistri(Request $request)
		{
			$validator = \Validator::make($request->all(), [
				'transport_type' => 'required',
				'digits_code' => 'required',
				'transfer_from' => 'required',
				'transfer_to' => 'required',
				'pullout_date' => 'required',
				'reason' => 'required',
			],
			[
				'transport_type.required' => 'You have to choose transport by.',
				'digits_code.required' => 'You have to add pullout items.',
				'transfer_to.required' => 'You have to choose pullout to store/warehouse.',
				'pullout_date.required' => 'You have to set date for pullout.',
				'reason.required' => 'You have to choose pullout reason.',
			]);
	
			if ($validator->fails()) {
				return redirect()->back()
				->withErrors($validator)
				->withInput();
			}

			$stDetails = array();
			$code_counter = CodeCounter::where('id', 1)->value('pullout_refcode');
        	$st_number = 'REF-'.str_pad($code_counter, 7, '0', STR_PAD_LEFT);
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
						'memo' => $request->memo,
						'pullout_date' => $request->pullout_date,
						'reason_id' => $request->reason,
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'transaction_type' => 'STW',
						'st_document_number' => $st_number,
						'sor_number' => $sor_number_stw,
						'st_status_id' => 2,
						'has_serial' => 1,
						'status' => 'PENDING',
						'step' => 2,
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
						'memo' => $request->memo,
						'pullout_date' => $request->pullout_date,
						'reason_id' => $request->reason,
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'transaction_type' => 'STW',
						'st_document_number' => $st_number,
						'sor_number' => $sor_number_stw,
						'st_status_id' => 2,
						'status' => 'PENDING',
						'step' => 2,
						'created_date' => date('Y-m-d'),
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

			if($record && !empty($st_number)){
				CodeCounter::where('id', 1)->increment('pullout_refcode');
				CRUDBooster::redirect(CRUDBooster::mainpath('print').'/'.$st_number,'','')->send();
			}
                
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No pullout has been created','danger')->send();
            }
		}
		
		public function saveCreateRMADistri(Request $request)
		{

			$validator = \Validator::make($request->all(), [
				'transport_type' => 'required',
				'digits_code' => 'required',
				'transfer_from' => 'required',
				'transfer_to' => 'required',
				'pullout_date' => 'required',
				'reason' => 'required',
			],
			[
				'transport_type.required' => 'You have to choose transport by.',
				'digits_code.required' => 'You have to add pullout items.',
				'transfer_to.required' => 'You have to choose pullout to store/warehouse.',
				'pullout_date.required' => 'You have to set date for pullout.',
				'reason.required' => 'You have to choose pullout reason.',
			]);
	
			if ($validator->fails()) {
				return redirect()->back()
				->withErrors($validator)
				->withInput();
			}
			
			$stDetails = array();
			$code_counter = CodeCounter::where('id', 1)->value('pullout_refcode');
        	$st_number = 'REF-'.str_pad($code_counter, 7, '0', STR_PAD_LEFT);
			
			//save ST
			foreach ($request->digits_code as $key_item => $value_item) {
				$serial = $value_item.'_serial_number';
				$i_problem = $value_item.'problems';
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
						'memo' => $request->memo,
						'pullout_date' => $request->pullout_date,
						'reason_id' => $request->reason,
						'purchase_date' => $request->purchase_date[$key_item],
						'problems' => implode(",",$request->$i_problem),
						'problem_detail' => $request->problem_detail[$key_item],
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'transaction_type' => 'RMA',
						'st_document_number' => $st_number,
						'sor_number' => NULL, //(in_array(CRUDBooster::myChannel(),[1])) ? $st_number: 
						'st_status_id' => 2,
						'has_serial' => 1,
						'status' => 'PENDING', 
						'step' => 2,
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
						'memo' => $request->memo,
						'pullout_date' => $request->pullout_date,
						'reason_id' => $request->reason,
						'purchase_date' => $request->purchase_date[$key_item],
						'problems' => implode(",",$request->$i_problem),
						'problem_detail' => $request->problem_detail[$key_item],
						'transaction_type' => 'RMA',
						'transport_types_id' => $request->transport_type,
						'hand_carrier' => $request->hand_carrier,
						'st_document_number' => $st_number,
						'sor_number' => NULL, //(in_array(CRUDBooster::myChannel(),[1])) ? $st_number: 
						'st_status_id' => 2,
						'status' => 'PENDING', 
						'step' => 2,
						'created_date' => date('Y-m-d'),
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
			
			if($record && !empty($st_number)){
				CodeCounter::where('id', 1)->increment('pullout_refcode');
				CRUDBooster::redirect(CRUDBooster::mainpath('print').'/'.$st_number,'','')->send();
			}
                
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No pullout has been created','danger')->send();
            }
		}

		public function scanItemSearch(Request $request)
		{
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
				}
				else{
				    
					foreach ($stock_status as $value) {
						
						if(is_array($value)){
							if($value['fqtyz'] != 0.000000 ){
								array_push($item_serials, $value['flotno']);
							}
						}
						
						else{
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

			if($items && $qty >= 0 && $items->reserve_qty > 0 && $request->channel != 2) {
				DB::table('items')
				->where('digits_code', $request->item_code)
				->decrement('reserve_qty');

				$data['status_no'] = 1;
				$data['message'] ='Item found!';
				$return_data['digits_code'] = $items->digits_code;
				$return_data['bea_item'] = $items->bea_item_id;
				$return_data['upc_code'] = $items->upc_code;
				$return_data['item_description'] = $items->item_description;
				$return_data['has_serial'] = $items->has_serial;
				$return_data['item_serial'] = $item_serials;
				$return_data['price'] = $items->store_cost;
				$data['items'] = $return_data;	
			}
			elseif($items && $qty >= 0 && $request->channel == 2) {

				$data['status_no'] = 1;
				$data['message'] ='Item found!';
				$return_data['digits_code'] = $items->digits_code;
				$return_data['bea_item'] = $items->bea_item_id;
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

		public function voidStockTransfer($st_number)
		{
			$isGisSt = DB::table('pullout')->where('st_document_number',$st_number)->first();
			if(!$isGisSt->request_type){
				if(substr($st_number,0,3) != "REF"){
					$voidST = app(POSPushController::class)->voidStockTransfer($st_number);
					
					if($voidST['data']['record']['fresult'] == "ERROR"){
						$error = $voidST['data']['record']['errors']['error'];
						CRUDBooster::redirect(CRUDBooster::mainpath(),'Fail! '.$error,'warning')->send();
					}
				}

				DB::table('pullout')->where('st_document_number',$st_number)->update([
					'status' => 'VOID',
					'updated_at' => date('Y-m-d H:i:s')
				]);
				CRUDBooster::redirect(CRUDBooster::mainpath(),'ST/REF#'.$st_number.' has been voided successfully!','success')->send();
			}else{
				$items = DB::table('pullout')->where('st_document_number',$st_number)->get();
				$mw_location = DB::table('stores')->where('id',$isGisSt->stores_id)->first();
				//get location
				$location = DB::connection('gis')->table('locations')->where('status','ACTIVE')
				->where('location_name',$mw_location->bea_so_store_name)->first();
	
				if($isGisSt->transaction_type == 'RMA'){
					//get sublocation
					$sublocation = DB::connection('gis')->table('sub_locations')->where('status','ACTIVE')
					->where('location_id',$location->id)->where('description','STOCK ROOM(D)')->first();
				}else{
					$sublocation = DB::connection('gis')->table('sub_locations')->where('status','ACTIVE')
					->where('location_id',$location->id)->where('description','STOCK ROOM')->first();
				}
			
				//get sub location in transit
				$from_intransit_gis_sub_location = DB::connection('gis')->table('sub_locations')->where('status','ACTIVE')
				->where('location_id',$location->id)->where('description','IN TRANSIT')->first();

				//UPDATE STATUS HEADER
				DB::table('pullout')->where('st_document_number',$st_number)->update([
					'status' => 'VOID',
					'updated_at' => date('Y-m-d H:i:s')
				]);
				//ADD QTY IN GIS INVENTORY LINES TO FROM LOCATION
				foreach($items as $key => $item){
					DB::connection('gis')->table('inventory_capsules')
					->leftjoin('inventory_capsule_lines','inventory_capsules.id','inventory_capsule_lines.inventory_capsules_id')
					->leftjoin('items','inventory_capsules.item_code','items.digits_code2')
					->where([
						'items.digits_code' => $item->item_code,
						'inventory_capsules.locations_id' => $location->id
					])
					->where('inventory_capsule_lines.sub_locations_id',$sublocation->id)
					->update([
						'qty' => DB::raw("qty + $item->quantity"),
						'inventory_capsule_lines.updated_at' => date('Y-m-d H:i:s')
					]);

					if($isGisSt->transaction_type == 'STW'){
						//UPDATE IN MW RESERVE QTY
						DB::table('items')
						->where([
							'upc_code' => $item->item_code,
						])
						->update([
							'reserve_qty' => DB::raw("reserve_qty + $item->quantity")
						]);
					}
					//ADD GIS MOVEMENT HISTORY
					//get item code
					$gis_mw_name = DB::connection('gis')->table('cms_users')->where('email','mw@gashapon.ph')->first();
					$item_code = DB::connection('gis')->table('items')->where('digits_code',$item->item_code)->first();
					$capsuleAction = DB::connection('gis')->table('capsule_action_types')->where('status','ACTIVE')
					->where('description','VOID')->first();
					DB::connection('gis')->table('history_capsules')->insert([
						'reference_number' => $st_number,
						'item_code' => $item_code->digits_code2,
						'capsule_action_types_id' => $capsuleAction->id,
						'locations_id' => $location->id,
						'from_sub_locations_id' => $sublocation->id,
						'qty' => $item->quantity,
						'created_at' => date('Y-m-d H:i:s'),
						'created_by' => $gis_mw_name->id
					]);
				}
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

			$data['stDetails'] = DB::table('pullout')
				->join('reason', 'pullout.reason_id', '=', 'reason.bea_mo_reason')
				->leftJoin('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
				->leftJoin('cms_users as approvedby', 'pullout.approved_by', '=', 'approvedby.id')
				->leftJoin('cms_users as rejectedby', 'pullout.rejected_by', '=', 'rejectedby.id')
				->where('st_document_number', $st_number)
				->select('pullout.*','reason.pullout_reason','reason.id as reason_id','transport_types.transport_type','rejectedby.name as rejectedby','approvedby.name as approvedby')
				->get();

			if(count($data['stDetails']) == 0){
				$data['stDetails'] = DB::table('pullout')
					->join('reason', 'pullout.reason_id', '=', 'reason.bea_so_reason')
					->leftJoin('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
					->leftJoin('cms_users as approvedby', 'pullout.approved_by', '=', 'approvedby.id')
				    ->leftJoin('cms_users as rejectedby', 'pullout.rejected_by', '=', 'rejectedby.id')
					->where('st_document_number', $st_number)
					->select('pullout.*','reason.pullout_reason','reason.id as reason_id','transport_types.transport_type','rejectedby.name as rejectedby','approvedby.name as approvedby')
					->get();
			}

			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id)->first();

			$data['transfer_to'] = DB::table('stores')->where('pos_warehouse',$data['stDetails'][0]->wh_to)->first();

			$items = DB::table('pullout')
			->where('st_document_number',$st_number)
			->select('id','item_code','quantity','problems','problem_detail','item_description')
			->get();

			$data['stQuantity'] =  DB::table('pullout')
			->where('st_document_number', $st_number)
			->sum('quantity');

			foreach ($items as $key => $value) {
	
				$serials = DB::table('serials')->where('pullout_id',$value->id)->select('serial_number')->get();
				$item_detail = DB::table('items')->where('digits_code', $value->item_code)->first();
				$gis_item_detail = DB::table('items')->where('upc_code', $value->item_code)->first();
				$serial_data = array();
				foreach ($serials as $serial) {
					array_push($serial_data, $serial->serial_number);
				}

				$item_data[$key] = [
					'digits_code' => $value->item_code,
					'upc_code' => $item_detail->upc_code ?? $gis_item_detail->digits_code,
					'brand' => $item_detail->brand ?? $gis_item_detail->brand,
					'item_description' => $item_detail != NULL ? $item_detail->item_description : $value->item_description,
					'price' => $item_detail->store_cost ?? $gis_item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'problems' => $value->problems.' : '.$value->problem_detail,
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

			$data['stDetails'] = DB::table('pullout')
					->join('reason', 'pullout.reason_id', '=', 'reason.bea_mo_reason')
					->leftJoin('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
					->where('st_document_number', $st_number)
					->select('pullout.*','reason.pullout_reason','reason.id as reason_id','transport_types.transport_type')
					->get();

			if(count($data['stDetails']) == 0){
				$data['stDetails'] = DB::table('pullout')
					->join('reason', 'pullout.reason_id', '=', 'reason.bea_so_reason')
					->leftJoin('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
					->where('st_document_number', $st_number)
					->select('pullout.*','reason.pullout_reason','reason.id as reason_id','transport_types.transport_type')
					->get();
			}		

			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id)->first();

			$data['transfer_to'] = DB::table('stores')->where('pos_warehouse',$data['stDetails'][0]->wh_to)->first();

			$items = DB::table('pullout')
			->where('st_document_number',$st_number)
			->select('id','item_code','quantity','item_description')
			->get();

			$data['stQuantity'] =  DB::table('pullout')
			->where('st_document_number', $st_number)
			->sum('quantity');

			foreach ($items as $key => $value) {

				$serials = DB::table('serials')->where('pullout_id',$value->id)->select('serial_number')->get();
				$item_detail = DB::table('items')->where('digits_code', $value->item_code)->first();
				$gis_item_detail = DB::table('items')->where('upc_code', $value->item_code)->first();

				$serial_data = array();
				foreach ($serials as $serial) {
					array_push($serial_data, $serial->serial_number);
				}

				$item_data[$key] = [
					'digits_code' => $value->item_code,
					'upc_code' => $item_detail->upc_code ?? $gis_item_detail->digits_code,
					'brand' => $item_detail->brand ?? $gis_item_detail->brand,
					'item_description' => $item_detail != NULL ? $item_detail->item_description : $value->item_description,
					'price' => $item_detail->store_cost ?? $gis_item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'problems' => $value->problems.' : '.$value->problem_detail,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;
			
			$this->cbView("pullout.schedule", $data);
		}

		public function getPrint($st_number)
		{

			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'Pullout Details';

			$data['stDetails'] = DB::table('pullout')
				->leftJoin('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
				->leftJoin('cms_users', 'pullout.schedule_by', '=', 'cms_users.id')
				->where('pullout.st_document_number', $st_number)
				->select('pullout.*','transport_types.transport_type','cms_users.name as scheduled_by')
				->get();
				
			$data['stReason'] = DB::table('reason')->where('bea_mo_reason', $data['stDetails'][0]->reason_id)->first();
			if(count((array)$data['stReason']) == 0){
				$data['stReason'] = DB::table('reason')->where('bea_so_reason', $data['stDetails'][0]->reason_id)->first();
			}
			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id)->first();

			$data['transfer_to'] = DB::table('stores')->where('pos_warehouse',$data['stDetails'][0]->wh_to)->first();

			$items = DB::table('pullout')
			->where('st_document_number',$st_number)
			->select('id','item_code','quantity','item_description')
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
					'brand' => $item_detail->brand,
					'item_description' => $item_detail != NULL ? $item_detail->item_description : $value->item_description,
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
			$pullouts = DB::table('pullout')
    			->where('status','FOR PROCESSING')//'FOR SCHEDULE','FOR RECEIVING',
    			->whereIn('channel_id',[2,4,7,10,11])
    			->whereNull('sor_number')->get();
			$record = false;
			foreach ($pullouts as $key => $value) {
				$sor_number = app(EBSPullController::class)->getOrderNumber($value->st_document_number);

				if(!empty($sor_number)){
					DB::table('pullout')->where('st_document_number', $value->st_document_number)->update([
						'sor_number' => $sor_number->order_number,
						'status' => ($value->transport_types_id == 1) ? 'FOR SCHEDULE' : 'FOR RECEIVING'
					]);
					$record = true;
				}
			}

			if($record){
				\Log::info('Update SOR: Pullout SOR number has been created!');
			}
			else{
				\Log::info('Update SOR: No pullout created in BEACH!');
			}
		}

		public function updateMwGisSORNumber()
		{
			$pullouts = DB::table('pullout')
    			->where('status','FOR PROCESSING')//'FOR SCHEDULE','FOR RECEIVING',
    			->whereNotNull('request_type')
				->where('transaction_type','RMA')
    			->whereNull('sor_number')->get();
			$record = false;
			
			foreach ($pullouts as $key => $value) {
				$sor_number = app(EBSPullController::class)->getOrderNumber($value->st_document_number);
				if(!empty($sor_number)){
					DB::table('pullout')->where('st_document_number', $value->st_document_number)->update([
						'sor_number' => $sor_number->order_number,
						'status' => ($value->transport_types_id == 1) ? 'FOR SCHEDULE' : 'FOR RECEIVING'
					]);
					$record = true;
				}
			}

			if($record){
				\Log::info('Update SOR: Pullout SOR number has been created!');
			}
			else{
				\Log::info('Update SOR: No pullout created in BEACH!');
			}
		}

		//RMA GIS MW
		public function getRMAGis(){
			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'Create ST RMA';

			if(CRUDBooster::isSuperadmin()){
				$data['transfer_from'] = DB::table('stores')
				->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name')->get();
			}
			else{
				$data['transfer_from'] = DB::table('stores')
				->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name')
				->whereIn('id',CRUDBooster::myStore())->get();
			}
			

			$data['transfer_to'] = DB::table('stores')
				->select('pos_warehouse','pos_warehouse_name')
				->whereNotIn('id',CRUDBooster::myStore())
				->where('pos_warehouse','DIGITSRMA')
				->where('status','ACTIVE')
				->get();

			$data['transport_types'] = DB::table('transport_types')
				->select('id','transport_type')
				// ->where('transport_type','Logistics')
				->where('status','ACTIVE')
				->get();
				
			$data['problems'] = DB::table('problems')
				->select('id','problem_details')
				->where('status','ACTIVE')
				->get();
			
			$data['reasons'] = DB::table('reason')
				->select('bea_mo_reason as bea_reason','pullout_reason')
				->where('transaction_type_id',2) //rma
				->where('status','ACTIVE')
				->get();
			
			$data['transfer_org'] = 225;
			if(CRUDBooster::myPrivilegeName() == "Online Requestor II"){
				$this->cbView("pullout.rma-online-fbd-scan", $data);
			}
			else{	
				$this->cbView("pullout.rma-gis-scan", $data);
			}
		}

		public function saveCreateRMAGisRma(Request $request)
		{
			$validator = \Validator::make($request->all(), [
				'transport_type' => 'required',
				'digits_code' => 'required',
				'transfer_from' => 'required',
				'transfer_to' => 'required',
				'pullout_date' => 'required',
				'reason' => 'required',
				// 'problems' => 'required',
			],
			[
				'transport_type.required' => 'You have to choose transport by.',
				'digits_code.required' => 'You have to add pullout items.',
				'transfer_to.required' => 'You have to choose pullout to store/warehouse.',
				'pullout_date.required' => 'You have to set date for pullout.',
				'reason.required' => 'You have to choose pullout reason.',
				// 'problems.required' => 'You have to choose item problem.',
			]);
	
			if ($validator->fails()) {
				return redirect()->back()
				->withErrors($validator)
				->withInput();
			}

			$mw_location = DB::table('stores')->where('id',$request->stores_id)->first();
			//get location
			$location = DB::connection('gis')->table('locations')->where('status','ACTIVE')
			->where('location_name',$mw_location->bea_so_store_name)->first();

			//get sublocation
			$sublocation = DB::connection('gis')->table('sub_locations')->where('status','ACTIVE')
			->where('location_id',$location->id)->where('description','STOCK ROOM(D)')->first();
	
			foreach ($request->digits_code as $key => $val) {
				$isQtyExceed = DB::connection('gis')->table('inventory_capsules')
				->leftjoin('inventory_capsule_lines','inventory_capsules.id','inventory_capsule_lines.inventory_capsules_id')
				->leftjoin('items','inventory_capsules.item_code','items.digits_code2')
				->where([
					'items.digits_code' => $val,
					'inventory_capsules.locations_id' => $location->id
				])
				->where('inventory_capsule_lines.sub_locations_id',$sublocation->id)
				->where('inventory_capsule_lines.qty','<',str_replace(',', '',$request->st_quantity[$key]))
				->exists();
				if($isQtyExceed){
					CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! Quantity must be equal or less than in GIS Inventory!','danger')->send();
				}
			}
			
			$stDetails = array();
			$posItemDetails = array();
			$record = false;
			
			$code_counter = CodeCounter::where('id', 1)->value('pullout_refcode');
			$st_number = 'REF-'.str_pad($code_counter, 7, '0', STR_PAD_LEFT);
	
			if(empty($st_number)){
				//back to old form
				CRUDBooster::redirect(CRUDBooster::adminpath('store_pullout'),'Failed! No pullout has been created.','danger')->send();
			}
	
			//save ST
			foreach ($request->digits_code as $key_item => $value_item) {
				$serial = $value_item.'_serial_number';
				$item_problems = $value_item.'problems';
				$item_serials = array();
				$st_qty = str_replace(',', '',$request->st_quantity[$key_item]); 

				$stDetails = [
					'item_code' => $value_item,
					'item_description' => $request->item_description[$key_item],
					'quantity' => $request->st_quantity[$key_item],
					'wh_from' => $request->transfer_from,
					'wh_to' => $request->transfer_to,
					'stores_id' => (int)(implode("",CRUDBooster::myStore())),
					'channel_id' => CRUDBooster::myChannel(),
					'memo' => $request->memo,
					'pullout_date' => $request->pullout_date,
					'reason_id' => $request->reason,
					'problems' => implode(",",$request->$item_problems),
					'problem_detail' => $request->problem_detail[$key_item],
					'transport_types_id' => $request->transport_type,
					'hand_carrier' => $request->hand_carrier,
					'transaction_type' => 'RMA',
					'st_document_number' => $st_number,
					'sor_number' => NULL, 
					'st_status_id' => 2,
					'has_serial' => 1,
					'status' => 'PENDING', 
					'step' => 2,
					'created_date' => date('Y-m-d'),
					'created_at' => date('Y-m-d H:i:s'),
					'request_type' => 'Gashapon'
				];

				//UPDATE IN GIS INVENTORY LINES
				DB::connection('gis')->table('inventory_capsules')
				->leftjoin('inventory_capsule_lines','inventory_capsules.id','inventory_capsule_lines.inventory_capsules_id')
				->leftjoin('items','inventory_capsules.item_code','items.digits_code2')
				->where([
					'items.digits_code' => $value_item,
					'inventory_capsules.locations_id' => $location->id
				])
				->where('inventory_capsule_lines.sub_locations_id',$sublocation->id)
				->update([
					'qty' => DB::raw("qty - $st_qty"),
					'inventory_capsule_lines.updated_at' => date('Y-m-d H:i:s')
				]);

				//ADD GIS MOVEMENT HISTORY
				//get item code
				$gis_mw_name = DB::connection('gis')->table('cms_users')->where('email','mw@gashapon.ph')->first();
				$item_code = DB::connection('gis')->table('items')->where('digits_code',$value_item)->first();
				$capsuleAction = DB::connection('gis')->table('capsule_action_types')->where('status','ACTIVE')
				->where('description','PULLOUT')->first();
				DB::connection('gis')->table('history_capsules')->insert([
					'reference_number' => $st_number,
					'item_code' => $item_code->digits_code2,
					'capsule_action_types_id' => $capsuleAction->id,
					'locations_id' => $location->id,
					'from_sub_locations_id' => $sublocation->id,
					'qty' => -1 * abs($st_qty),
					'created_at' => date('Y-m-d H:i:s'),
					'created_by' => $gis_mw_name->id
				]);
				
				$pullout_id = Pullout::insertGetId($stDetails);
				$record = true;
				
			}

			DB::table('code_counter')->where('id', 1)->increment('pullout_refcode');

			if($record && !empty($st_number)){
			    CRUDBooster::insertLog(trans("crudbooster.str_created", ['ref_number' =>$st_number]));
                CRUDBooster::redirect(CRUDBooster::mainpath('print').'/'.$st_number,'','')->send();
			}
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No pullout has been created','danger')->send();
            }
		}

		//STW GIS MW
		public function getSTWGis()
		{
			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'Create STW GIS';

			if(CRUDBooster::isSuperadmin()){
				$data['transfer_from'] = DB::table('stores')
				->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name','doo_subinventory as subinventory')->get();
			}
			else{
				$data['transfer_from'] = DB::table('stores')
				->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name','doo_subinventory as subinventory')
				->whereIn('id',CRUDBooster::myStore())->get();
			}
			

			$data['transfer_to'] = DB::table('stores')
				->select('pos_warehouse','pos_warehouse_name')
				->whereNotIn('id',CRUDBooster::myStore())
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
			
			if(in_array(CRUDBooster::myChannel(),[6,7,10,11])){ //con,out,crp,dlr
			    $this->cbView("pullout.stw-distri-scan", $data);
			}
			else{
			    $this->cbView("pullout.stw-gis-scan", $data);
			}
			
		}

		//STW GIS SAVE
		public function saveCreateStwGisRma(Request $request)
		{
			$validator = \Validator::make($request->all(), [
				'transport_type' => 'required',
				'digits_code' => 'required',
				'transfer_from' => 'required',
				'transfer_to' => 'required',
				'pullout_date' => 'required',
				'reason' => 'required',
				// 'problems' => 'required',
			],
			[
				'transport_type.required' => 'You have to choose transport by.',
				'digits_code.required' => 'You have to add pullout items.',
				'transfer_to.required' => 'You have to choose pullout to store/warehouse.',
				'pullout_date.required' => 'You have to set date for pullout.',
				'reason.required' => 'You have to choose pullout reason.',
				// 'problems.required' => 'You have to choose item problem.',
			]);
	
			if ($validator->fails()) {
				return redirect()->back()
				->withErrors($validator)
				->withInput();
			}
		
			$mw_location = DB::table('stores')->where('id',$request->stores_id)->first();
			//get location
			$location = DB::connection('gis')->table('locations')->where('status','ACTIVE')
			->where('location_name',$mw_location->bea_so_store_name)->first();

			//get sublocation
			$sublocation = DB::connection('gis')->table('sub_locations')->where('status','ACTIVE')
			->where('location_id',$location->id)->where('description','STOCK ROOM')->first();
	
			foreach ($request->digits_code as $key => $val) {
				$isQtyExceed = DB::connection('gis')->table('inventory_capsules')
				->leftjoin('inventory_capsule_lines','inventory_capsules.id','inventory_capsule_lines.inventory_capsules_id')
				->leftjoin('items','inventory_capsules.item_code','items.digits_code2')
				->where([
					'items.digits_code' => $val,
					'inventory_capsules.locations_id' => $location->id
				])
				->where('inventory_capsule_lines.sub_locations_id',$sublocation->id)
				->where('inventory_capsule_lines.qty','<',str_replace(',', '',$request->st_quantity[$key]))
				->exists();
				if($isQtyExceed){
					CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! Quantity must be equal or less than in GIS Inventory!','danger')->send();
				}
			}
			
			$stDetails = array();
			$posItemDetails = array();
			$record = false;
			
			$code_counter = CodeCounter::where('id', 1)->value('pullout_refcode');
			$st_number = 'REF-'.str_pad($code_counter, 7, '0', STR_PAD_LEFT);
	
			if(empty($st_number)){
				//back to old form
				CRUDBooster::redirect(CRUDBooster::adminpath('store_pullout'),'Failed! No pullout has been created.','danger')->send();
			}
	
			//save ST
			foreach ($request->digits_code as $key_item => $value_item) {
				$serial = $value_item.'_serial_number';
				$item_problems = $value_item.'problems';
				$item_serials = array();
				$st_qty = str_replace(',', '',$request->st_quantity[$key_item]); 

				$stDetails = [
					'item_code' => $value_item,
					'item_description' => $request->item_description[$key_item],
					'quantity' => $request->st_quantity[$key_item],
					'wh_from' => $request->transfer_from,
					'wh_to' => $request->transfer_to,
					'stores_id' => (int)(implode("",CRUDBooster::myStore())),
					'channel_id' => CRUDBooster::myChannel(),
					'memo' => $request->memo,
					'pullout_date' => $request->pullout_date,
					'reason_id' => $request->reason,
					// 'problems' => implode(",",$request->$item_problems),
					'problem_detail' => $request->problem_detail[$key_item],
					'transport_types_id' => $request->transport_type,
					'hand_carrier' => $request->hand_carrier,
					'transaction_type' => 'STW',
					'st_document_number' => $st_number,
					'sor_number' => NULL, 
					'st_status_id' => 2,
					'has_serial' => 1,
					'status' => 'PENDING', 
					'step' => 2,
					'created_date' => date('Y-m-d'),
					'created_at' => date('Y-m-d H:i:s'),
					'request_type' => 'Gashapon'
				];

				//UPDATE IN GIS INVENTORY LINES
				DB::connection('gis')->table('inventory_capsules')
				->leftjoin('inventory_capsule_lines','inventory_capsules.id','inventory_capsule_lines.inventory_capsules_id')
				->leftjoin('items','inventory_capsules.item_code','items.digits_code2')
				->where([
					'items.digits_code' => $value_item,
					'inventory_capsules.locations_id' => $location->id
				])
				->where('inventory_capsule_lines.sub_locations_id',$sublocation->id)
				->update([
					'qty' => DB::raw("qty - $st_qty"),
					'inventory_capsule_lines.updated_at' => date('Y-m-d H:i:s')
				]);

				//UPDATE IN MW RESERVE QTY
				DB::table('items')
				->where([
					'upc_code' => $value_item,
				])
				->update([
					'reserve_qty' => DB::raw("reserve_qty - $st_qty")
				]);

				//ADD GIS MOVEMENT HISTORY
				//get item code
				$gis_mw_name = DB::connection('gis')->table('cms_users')->where('email','mw@gashapon.ph')->first();
				$item_code = DB::connection('gis')->table('items')->where('digits_code',$value_item)->first();
				$capsuleAction = DB::connection('gis')->table('capsule_action_types')->where('status','ACTIVE')
				->where('description','PULLOUT')->first();
				DB::connection('gis')->table('history_capsules')->insert([
					'reference_number' => $st_number,
					'item_code' => $item_code->digits_code2,
					'capsule_action_types_id' => $capsuleAction->id,
					'locations_id' => $location->id,
					'from_sub_locations_id' => $sublocation->id,
					'qty' => -1 * abs($st_qty),
					'created_at' => date('Y-m-d H:i:s'),
					'created_by' => $gis_mw_name->id
				]);
				
				$pullout_id = Pullout::insertGetId($stDetails);
				$record = true;
				
			}

			DB::table('code_counter')->where('id', 1)->increment('pullout_refcode');

			if($record && !empty($st_number)){
			    CRUDBooster::insertLog(trans("crudbooster.str_created", ['ref_number' =>$st_number]));
                CRUDBooster::redirect(CRUDBooster::mainpath('print').'/'.$st_number,'','')->send();
			}
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No pullout has been created','danger')->send();
            }
		}

		//STW GIS SCAN
		public function scanStwGisMwItemSearch(Request $request){
			$data = array();
			$data['status_no'] = 0;
			$data['message'] ='No item found!';
			$mw_location = DB::table('stores')->where('id',(int)(implode("",CRUDBooster::myStore())))->first();
			//get location
			$location = DB::connection('gis')->table('locations')->where('status','ACTIVE')
			->where('location_name',$mw_location->bea_so_store_name)->first();
	
			//get sublocation
			$sublocation = DB::connection('gis')->table('sub_locations')->where('status','ACTIVE')
			->where('location_id',$location->id)->where('description','STOCK ROOM')->first();
	
			$inventory_gis = DB::connection('gis')->table('inventory_capsules')
			->leftjoin('inventory_capsule_lines','inventory_capsules.id','inventory_capsule_lines.inventory_capsules_id')
			->leftjoin('items','inventory_capsules.item_code','items.digits_code2')
			->where([
				'items.digits_code' => $request->item_code,
				'inventory_capsules.locations_id' => $location->id
			])
			->where('inventory_capsule_lines.sub_locations_id',$sublocation->id)
			->first();

			$mwReservalbleQty = DB::table('items')->where('upc_code',$request->item_code)->first();

			if($inventory_gis && $inventory_gis->qty >= 0 && isset($mwReservalbleQty)) {
				$data['status_no'] = 1;
				$data['message'] ='Item found!';
				$return_data['digits_code'] = $inventory_gis->digits_code;
				$return_data['item_description'] = $inventory_gis->item_description;
				$return_data['location'] = $location->location_name;
				$return_data['location_id_from'] = $location->id;
				$return_data['sub_location_id_from'] = $sublocation->id;
				$return_data['orig_qty'] = $inventory_gis->qty;
				$return_data['mwReservableQty'] = $mwReservalbleQty->reserve_qty;
				$data['items'] = $return_data;
			}
		
			echo json_encode($data);
			exit;
		   
		}
	}
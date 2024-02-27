<?php namespace App\Http\Controllers;

	use Session;
	use DB;
	use CRUDBooster;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\File;
	use Illuminate\Http\Request;
	use App\ApprovalMatrix;
	use App\GisPullLines;
	use App\GisPull;

	class AdminStoreTransferGisController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->col[] = ["label"=>"Ref Number","name"=>"ref_number"];
			$this->col[] = ["label"=>"Status Id","name"=>"status_id","join"=>"status_workflows,workflow_status"];
			$this->col[] = ["label"=>"Total Qty","name"=>"quantity_total"];
			$this->col[] = ["label"=>"From Location","name"=>"stores_id","join"=>"stores,bea_so_store_name"];
			$this->col[] = ["label"=>"To Location","name"=>"stores_id_destination","join"=>"stores,bea_so_store_name"];
			
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
			//$this->addaction[] = ['title'=>'Details','url'=>CRUDBooster::mainpath('gis-details').'/[ref_number]','icon'=>'fa fa-eye','color'=>'primary'];

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
			if(CRUDBooster::getCurrentMethod() == 'getIndex' && !in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL","Approver"])){
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
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	if($column_index == 1){
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

		public function getGisScan(){
			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'Create GIS STS';

			if(CRUDBooster::isSuperadmin()){
				$data['transfer_from'] = DB::table('stores')
				->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name')
				->where('status', 'ACTIVE')
				->orderBy('pos_warehouse_name', 'ASC')
				->get();
			}
			else{
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
				$this->cbView("stock-transfer.scan-gis", $data);
			}
			
		}

		public function scanItemSearchGis(Request $request){
			$data = array();
			$data['status_no'] = 0;
			$data['message'] ='No item found!';
			$mw_location = DB::table('stores')->where('id',CRUDBooster::myStore())->first();
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
			if($inventory_gis && $inventory_gis->qty >= 0) {
				$data['status_no'] = 1;
				$data['message'] ='Item found!';
				$return_data['digits_code'] = $inventory_gis->digits_code;
				$return_data['item_description'] = $inventory_gis->item_description;
				$return_data['location'] = $location->location_name;
				$return_data['location_id_from'] = $location->id;
				$return_data['sub_location_id_from'] = $sublocation->id;
				$return_data['orig_qty'] = $inventory_gis->qty;
				$data['items'] = $return_data;
			}
			echo json_encode($data);
            exit;
		}

		public function saveCreateGisST(Request $request){
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
			$to_mw_location = DB::table('stores')->where('id',$request->stores_id_destination)->first();
			$to_gis_location = DB::connection('gis')->table('locations')->where('status','ACTIVE')
			->where('location_name',$to_mw_location->bea_so_store_name)->first();
	
			//CREATE HEADER
			$count_header = DB::table('gis_pulls')->count();
			$header_ref   = str_pad($count_header + 1, 7, '0', STR_PAD_LEFT);			
			$st_ref_no	  = "ST-".$header_ref;

			$getLastId = GisPull::Create(
				[
					'ref_number'             => $st_ref_no,
					'status_id'              => 1,
					'quantity_total'         => $request->total_quantity,
					'memo'                   => $request->memo,
					'stores_id'              => (int)(implode("",CRUDBooster::myStore())),
					'location_from'          => $request->transfer_from,
					'stores_id_destination'  => $request->stores_id_destination,
					'location_to'            => $request->transfer_to,
					'transport_types_id'     => $request->transport_type,
					'reason_id'              => $request->reason, 
					'transfer_date'          => $request->transfer_date,
					'hand_carrier'           => $request->hand_carrier,
					'created_date'           => date('Y-m-d'),
					'created_at'             => date('Y-m-d H:i:s')
				]
			);     

			$id = $getLastId->id;
			$st_header = DB::table('gis_pulls')->where('id',$id)->first();
			
			foreach ($request->digits_code as $key_item => $value_item) {
				$st_qty = str_replace(',', '',$request->st_quantity[$key_item]); 
				//INSERT IN MW GIS PULL TABLE
				$stDetails = [
					'gis_pull_id'      => $id,
					'item_code'        => $value_item,
					'item_description' => $request->item_description[$key_item],
					'quantity'         => $st_qty,
					'created_at'       => date('Y-m-d H:i:s')
				];

				//UPDATE IN GIS INVENTORY LINES
				DB::connection('gis')->table('inventory_capsules')
				->leftjoin('inventory_capsule_lines','inventory_capsules.id','inventory_capsule_lines.inventory_capsules_id')
				->leftjoin('items','inventory_capsules.item_code','items.digits_code2')
				->where([
					'items.digits_code' => $value_item,
					'inventory_capsules.locations_id' => $request->location_id_from
				])
				->where('inventory_capsule_lines.sub_locations_id',$request->sub_location_id_from)
				->update([
					'qty' => DB::raw("qty - $st_qty"),
					'inventory_capsule_lines.updated_at' => date('Y-m-d H:i:s')
				]);

				//INSERT IN MW GIS PULL LINES
				$record = GisPullLines::insert($stDetails);
			}

			if($record){
				CRUDBooster::redirect(CRUDBooster::mainpath(), trans("Stock transfer has been created!",['reference_number'=>$st_header->ref_number]), 'info');
            
			}else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No stock transfer has been created','danger')->send();
            }

		}

		public function getDetail($id){
			if(!CRUDBooster::isRead() && $this->global_privilege == false || $this->button_detail == false) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$this->cbLoader();
			$data = array();
			$data['page_title'] = 'Stock Transfer GIS Details';
			$data['header'] = DB::table('gis_pulls')->where('gis_pulls.id',$id)
								->leftjoin('reason','gis_pulls.reason_id','reason.id')
								->leftjoin('cms_users AS approver','gis_pulls.approved_by','approver.id')
								->leftjoin('cms_users AS receiver','gis_pulls.received_by','receiver.id')
								->leftjoin('cms_users AS rejector','gis_pulls.rejected_by','rejector.id')
								->select('gis_pulls.*',
										 'gis_pulls.id AS gp_id',
										 'reason.*',
										 'approver.name AS approver',
										 'receiver.name AS receiver',
										 'rejector.name AS rejector'
										 )
								->first();
			$data['items'] = DB::table('gis_pull_lines')->where('gis_pull_id',$id)->get();
			// dd($data['items'],$data['header']);
			$this->cbView("stock-transfer.gis-st-detail", $data);
		}
	}
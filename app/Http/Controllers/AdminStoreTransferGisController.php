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
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Redirect;
	class AdminStoreTransferGisController extends \crocodicstudio\crudbooster\controllers\CBController {
		private const Pending = 1;
		private const Void    = 5;
		private const ForSchedule    = 6;
		private const ForReceiving   = 2;
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
			//$this->addaction[] = ['title'=>'Details','url'=>CRUDBooster::mainpath('gis-details').'/[ref_number]','icon'=>'fa fa-eye','color'=>'primary'];
			$this->addaction[] = [
				'title'=>'Void ST',
				'url'=>CRUDBooster::mainpath('void-st-gis/[id]'),
				'icon'=>'fa fa-times',
				'color'=>'danger',
				'showIf'=>"[status_id]==".self::Pending."",
				'confirmation'=>'yes',
				'confirmation_title'=>'Confirm Voiding',
				'confirmation_text'=>'Are you sure to VOID this transaction?'
			];
			if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeId() ,[4,25])){
				$this->addaction[] = ['title'=>'Schedule','url'=>CRUDBooster::mainpath('schedule-gis').'/[id]','icon'=>'fa fa-calendar','color'=>'warning','showIf'=>"[status_id]==".self::ForSchedule.""];
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
			$gashapon = DB::table('stores')->where('id',(int)(implode("",CRUDBooster::myStore())))->first();
	
			if(CRUDBooster::getCurrentMethod() == 'getIndex' && in_array(explode(".",$gashapon->bea_so_store_name)[0],["GASHAPON"])){
				$this->index_button[] = ['label'=>'Create GIS STS','url'=>route('st.gis.scanning'),'icon'=>'fa fa-plus','color'=>'success'];
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
			if(CRUDBooster::isSuperadmin()){
				$query->whereNull('gis_pulls.deleted_at')->orderBy('gis_pulls.status_id', 'DESC')->orderBy('gis_pulls.id', 'DESC');
			}elseif (in_array(CRUDBooster::myPrivilegeId() ,[4,25])) {
				$query->where('gis_pulls.status_id',self::ForSchedule)->where('transport_types_id',1);
			}
			else{
				$query->where('gis_pulls.stores_id', CRUDBooster::myStore());
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
            
			
			$data['transfer_to'] = DB::table('stores')
				->select('id','pos_warehouse','pos_warehouse_name')
				->whereNotIn('id',CRUDBooster::myStore())
				->where('bea_so_store_name','LIKE','%'.'GASHAPON'.'%')
				->where('status','ACTIVE')
				->orderBy('pos_warehouse_name', 'ASC')
				->get();
			
			$this->cbView("stock-transfer.scan-gis", $data);
			
			
		}

		public function scanItemSearchGis(Request $request){
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
			$to_gis_sub_location = DB::connection('gis')->table('sub_locations')->where('status','ACTIVE')
			->where('location_id',$to_gis_location->id)->where('description','STOCK ROOM')->first();
			if(!$to_gis_location){
				CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! Location in GIS not match!','danger')->send();
			}
			foreach ($request->digits_code as $key => $val) {
				$isToLocationExist = DB::connection('gis')->table('inventory_capsules')
				->leftjoin('inventory_capsule_lines','inventory_capsules.id','inventory_capsule_lines.inventory_capsules_id')
				->leftjoin('items','inventory_capsules.item_code','items.digits_code2')
				->where([
					'items.digits_code' => $val,
					'inventory_capsules.locations_id' => $to_gis_location->id
				])
				->where('inventory_capsule_lines.sub_locations_id',$to_gis_sub_location->id)
				->exists();
				if(!$isToLocationExist){
					CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! Jan Code not exist in location to transfer!','danger')->send();
				}
			}
			//CREATE HEADER
			$count_header = DB::table('gis_pulls')->count();
			$header_ref   = str_pad($count_header + 1, 7, '0', STR_PAD_LEFT);			
			$st_ref_no	  = "ST-".$header_ref;

			$getLastId = GisPull::Create(
				[
					'ref_number'             => $st_ref_no,
					'status_id'              => self::Pending,
					'quantity_total'         => $request->total_quantity,
					'memo'                   => $request->memo,
					'stores_id'              => (int)(implode("",CRUDBooster::myStore())),
					'location_id_from'       => $request->location_id_from,
					'sub_location_id_from'   => $request->sub_location_id_from,
					'location_from'          => $request->transfer_from,
					'stores_id_destination'  => $request->stores_id_destination,
					'location_id_to'         => $to_gis_location->id,
					'sub_location_id_to'     => $to_gis_sub_location->id,
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
			$data['header'] = GisPull::stGisHeader($id);
			$data['items'] = DB::table('gis_pull_lines')->where('gis_pull_id',$id)->get();
			// dd($data['header']);
			$this->cbView("stock-transfer.gis-st-detail", $data);
		}

		public function getVoidStGis($id){
			$header = DB::table('gis_pulls')->where('id',$id)->first();
			$items = DB::table('gis_pull_lines')->where('gis_pull_id',$id)->get();
		
			//UPDATE STATUS HEADER
			DB::table('gis_pulls')->where('id',$id)->update([
				'status_id' => self::Void
			]);

			//ADD QTY IN GIS INVENTORY LINES TO FROM LOCATION
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

		public function getScheduleGis($id){
			if(!CRUDBooster::isRead() && $this->global_privilege == false || $this->button_detail == false) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$this->cbLoader();
			$data = array();
			$data['page_title'] = 'Stock Transfer GIS Details';
			$data['header'] = GisPull::stGisHeader($id);
			$data['items'] = DB::table('gis_pull_lines')->where('gis_pull_id',$id)->get();
			// dd($data['header']);
			$this->cbView("stock-transfer.gis-st-schedule", $data);
		}

		public function saveScheduleGis(Request $request){
			$record = DB::table('gis_pulls')
				->where('id',$request->header_id)
				->update([
					'schedule_at' => $request->schedule_date,
					'schedule_by' => CRUDBooster::myId(),
					'status_id' => self::ForReceiving
				]);

			if($record)
                CRUDBooster::redirect(CRUDBooster::mainpath('print-gis').'/'.$request->header_id,'','')->send();
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),'Failed! No transaction has been scheduled for transfer.','danger')->send();
            }
		}

		public function getPrintGis($id){
			if(!CRUDBooster::isRead() && $this->global_privilege == false || $this->button_detail == false) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$this->cbLoader();
			$data = array();
			$data['page_title'] = 'Stock Transfer GIS Details';
			$data['header'] = GisPull::stGisHeader($id);
			$data['items'] = DB::table('gis_pull_lines')->where('gis_pull_id',$id)->get();
			// dd($data['header']);
			$this->cbView("stock-transfer.gis-st-print", $data);
		}
	}
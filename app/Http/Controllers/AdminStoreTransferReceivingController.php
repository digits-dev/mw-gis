<?php namespace App\Http\Controllers;

	use Session;
	use DB;
	use CRUDBooster;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\File;
	use Illuminate\Http\Request;
	use App\PosPullHeader;
	use App\PosPullLines;

	class AdminStoreTransferReceivingController extends \crocodicstudio\crudbooster\controllers\CBController {
		private const Pending = 'PENDING';
		private const Void    = 'VOID';
		private const ForSchedule  = 'FOR SCHEDULE';
		private const ForReceiving = 'FOR RECEIVING';
		private const Confirmed = 'CONFIRMED';
		private const Received = 'RECIEVED';

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "st_document_number";
			$this->limit = "20";
			$this->orderby = "created_date,desc";
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
			$this->table = "pos_pull_headers";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"ST #","name"=>"st_document_number"];
			$this->col[] = ["label"=>"From WH","name"=>"stores_id","join"=>"stores,pos_warehouse_name"];
			$this->col[] = ["label"=>"To WH","name"=>"stores_id_destination","join"=>"stores,pos_warehouse_name"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
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
			$this->addaction[] = ['title'=>'Receive ST','url'=>CRUDBooster::mainpath().'/[st_document_number]','icon'=>'fa fa-cube','color'=>'info', 'showIf' => "[status]=='FOR RECEIVING'"];
			$this->addaction[] = ['title'=>'Detail','url'=>CRUDBooster::mainpath().'/details/[st_document_number]','icon'=>'fa fa-eye','color'=>'primary'];
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
				
				if(CRUDBooster::myPrivilegeName() != "Online WSDM") {
					if(CRUDBooster::myPrivilegeName() == "Online Ops") {
						$query->select('pos_pull_headers.st_document_number','pos_pull_headers.wh_from','pos_pull_headers.wh_to','pos_pull_headers.status')
						->where('pos_pull_headers.channel_id', CRUDBooster::myChannel())->where('pos_pull_headers.status',self::ForReceiving);   
					}
					else{
						$query->select('pos_pull_headers.st_document_number','pos_pull_headers.wh_from','pos_pull_headers.wh_to','pos_pull_headers.status')
						->whereIn('pos_pull_headers.stores_id_destination', CRUDBooster::myStore())->where('pos_pull_headers.status',self::ForReceiving);   
					}
				}
				else{
					$query->select('pos_pull_headers.st_document_number','pos_pull_headers.wh_from','pos_pull_headers.wh_to','pos_pull_headers.status')
					->whereIn('pos_pull_headers.stores_id', CRUDBooster::myStore())->where('pos_pull_headers.status',self::ForReceiving);   
				}
			}  
			else{
				$query->select('pos_pull_headers.st_document_number','pos_pull_headers.wh_from','pos_pull_headers.wh_to','pos_pull_headers.status')
				->where('pos_pull_headers.status',self::ForReceiving);   
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
				if($column_value == self::Pending){
					$column_value = '<span class="label label-warning">'.self::Pending.'</span>';
				}
				else if($column_value == self::ForSchedule){
					$column_value = '<span class="label label-warning">'.self::ForSchedule.'</span>';
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

		public function getReceiving($st_number)
		{
			$this->cbLoader();
			$data = array();
			$data['page_title'] = 'Receive STS';
			$data['stDetails'] = PosPullHeader::getDetails($st_number)->first();
			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails']->stores_id)->first();
			$data['transfer_to'] = DB::table('stores')->where('id',$data['stDetails']->stores_id_destination)->first();
			$items = PosPullLines::getItems($data['stDetails']->id)->get();
			$data['stQuantity'] =  PosPullLines::getStQuantity($data['stDetails']->id);

			foreach ($items as $key => $value) {
				$serials = DB::table('serials')->where('pos_pull_id',$value->id)->select('serial_number')->get();
				$item_detail = DB::table('items')->where('digits_code', $value->item_code)->first();

				$serial_data = array();
				foreach ($serials as $serial) {
					array_push($serial_data, $serial->serial_number);
				}

				$item_data[$key] = [
					'digits_code' => $value->item_code,
					'bea_item_id' => $item_detail->bea_item_id,
					'upc_code' => $item_detail->upc_code,
					'item_description' => $item_detail != NULL ? $item_detail->item_description : $value->item_description,
					'price' => $item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'st_serial_numbers' => $serial_data
				];
			}
			
			$data['items'] = $item_data;
			if($data['transfer_to']->channel_id == 4){
				return view("stock-transfer.receive-online-fbd", $data);
			}
			else if(CRUDBooster::myBulkReceiving()[0] == 1){
			    return view("stock-transfer.sys-receive", $data);
			}
			else{
				return view("stock-transfer.receive", $data);
			}
		}

		public function getDetail($st_number)
		{
			$this->cbLoader();
			$data = array();
			$data['page_title'] = 'Stock Transfer Details';
			$data['stDetails'] = PosPullHeader::getDetails($st_number)->first();
			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails']->stores_id)->first();
			$data['transfer_to'] = DB::table('stores')->where('id',$data['stDetails']->stores_id_destination)->first();
			$items = PosPullLines::getItems($data['stDetails']->id)->get();
			$data['stQuantity'] =  PosPullLines::getStQuantity($data['stDetails']->id);

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
			return view("stock-transfer.detail", $data);
		}

		public function serialSearch(Request $request)
		{
			$data = array();
            $data['status_no'] = 0;
			$data['message'] ='No item found!';
			$quantity = $request->quantity;
			
			$items = DB::table('items')
				->where('digits_code', $request->item_code)
				->select('allowed_multi_serial')
				->first();
			
			$sts = DB::table('pos_pull')
				->leftjoin('pos_pull_headers', 'pos_pull.pos_pull_header_id','pos_pull_headers.id')
				->where('pos_pull.item_code', $request->item_code)
				->where('pos_pull_headers.st_document_number', $request->st_number)
				->first();

			$serial = DB::table('serials')
				->where('pos_pull_id', $sts->id)
				//->where('serial_number', $request->serial_number)
				->whereRaw("BINARY `serial_number`='{$request->serial_number}'")
				->first();
					
			if($serial && $serial->counter == 0) {
				$data['status_no'] = 1;
				$data['message'] ='Serial found!';
				$return_data['digits_code'] = $sts->item_code;
				$data['serials'] = $return_data;

				DB::table('serials')
					->where('pos_pull_id', $sts->id)
					->where('serial_number', $request->serial_number)
					->update([
						'status' => 'RECEIVED',
						'counter' => ($serial->counter + 1)
					]);
			}
			elseif($items->allowed_multi_serial == 1){
			    $data['status_no'] = 1;
				$data['message'] ='Serial found!';
				$return_data['digits_code'] = $sts->item_code;
				$data['serials'] = $return_data;
			}
			            
            echo json_encode($data);
			exit;
		}

		public function resetSerialCounter(Request $request)
		{
			
			$items = DB::table('pos_pull')
				->leftjoin('pos_pull_headers', 'pos_pull.pos_pull_header_id','pos_pull_headers.id')
				->where('pos_pull_headers.st_document_number', $request->st_number)
				->where('pos_pull.has_serial',1)
				->get();

			foreach ($items as $item) {
				DB::table('serials')
					->where('pos_pull_id', $item->id)
					->update([
						'counter' => 0
					]);
			}

            echo json_encode($data['message'] = 'done');
            exit;
		}

		public function itemSearch(Request $request)
		{
			$data = array();
            $data['status_no'] = 0;
            $data['message'] ='No item found!';
			
			$items = DB::table('pos_pull')
				->where('pos_pull.item_code', $request->item_code)
				->leftjoin('pos_pull_headers', 'pos_pull.pos_pull_header_id','pos_pull_headers.id')
				->where('pos_pull_headers.st_document_number', $request->st_number)
				->first();
					
			if($items) {
				
				$data['status_no'] = 1;
				$data['message'] ='Item found!';
				$return_data['digits_code'] = $items->item_code;
				$return_data['serialized'] = $items->has_serial;
				$data['items'] = $return_data;	
			}
			            
            echo json_encode($data);
            exit;
		}
        /*
		public function saveSTSReceiving(Request $request)
		{
			$posItemDetails = array();

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

			$refcode = substr(CRUDBooster::myName(), 0, 3).date('YmdHis');
			$transfer_dest = (empty($request->transfer_to_rma)) ? $request->transfer_to : $request->transfer_to_rma;
			$transfer_branch = (empty($request->from_transfer_rma_branch)) ? $request->from_transfer_branch : $request->from_transfer_rma_branch;
			$transfer_origin = (empty($request->from_transfer_rma)) ? $request->from_transfer_transit : $request->from_transfer_rma;

			$postedST = app(POSPushController::class)->posCreateStockTransfer($refcode, $transfer_branch, $transfer_origin, $transfer_dest, 'STS', $posItemDetails);
			
			// $postedST = app(POSPushController::class)->posCreateStockTransfer($refcode, $request->from_transfer_branch, $request->from_transfer_transit, $request->transfer_to, 'STS', $posItemDetails);
			\Log::info('sts create rcv ST: '.json_encode($postedST));
			$record = false;
			$st_number = $postedST['data']['record']['fdocument_no'];
			if($postedST['data']['record']['fresult'] != "ERROR"){
				DB::table('pos_pull')->where('st_document_number', $request->st_number)->update([
					'status' => 'RECEIVED',
					'received_st_number' => $st_number,
					'received_st_date' => date('Y-m-d'),
					'updated_at' => date('Y-m-d H:i:s')
				]);

				$record = true;
			}
			
			if($record)
                CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' received successfully!','success')->send();
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),$postedST['data']['record']['errors']['error'].'. ST# '.$request->st_number.' failed to be received!','danger')->send();
            }
		}

		public function saveSTSOnlineReceiving(Request $request)
		{
			
			$posItemDetails = array();

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

			$refcode = substr(CRUDBooster::myName(), 0, 3).date('YmdHis');
			$postedST = app(POSPushController::class)->posCreateStockTransfer($refcode, $request->from_transfer_branch, $request->from_transfer_transit, $request->transfer_to, 'STS-'.$request->customer_name, $posItemDetails);
			\Log::info('sts create rcv ST: '.json_encode($postedST));
			$record = false;
			$st_number = $postedST['data']['record']['fdocument_no'];
			if($postedST['data']['record']['fresult'] != "ERROR"){
				DB::table('pos_pull')->where('st_document_number', $request->st_number)->update([
					'status' => 'RECEIVED',
					'received_st_number' => $st_number,
					'received_st_date' => date('Y-m-d'),
					'updated_at' => date('Y-m-d H:i:s')
				]);

				$record = true;
			}
			foreach ($request->digits_code as $key_item => $value_item) {
				app(EBSPushController::class)->createSIT($st_number, 
					$request->bea_item_id[$key_item], 
					$request->st_quantity[$key_item], 
					$request->from_transfer_subinventory,
					$request->transfer_to_subinventory,
					''
				);
			}
			
			if($record)
                CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' received successfully!','success')->send();
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),$postedST['data']['record']['errors']['error'].'. ST# '.$request->st_number.' failed to received!','danger')->send();
            }
		}
		
		public function saveSTSOnlinePickConfirm(Request $request)
		{
			$posItemDetails = array();

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

			$refcode = 'BEAPOSMW-STS-'.date('His');
			$postedST = app(POSPushController::class)->posCreateStockTransfer($refcode, $request->from_transfer_branch, $request->from_transfer_transit, $request->transfer_to, 'STS-'.$request->customer_name, $posItemDetails);
			\Log::info('sts create rcv ST: '.json_encode($postedST));
			$record = false;
			$st_number = $postedST['data']['record']['fdocument_no'];
			if($postedST['data']['record']['fresult'] != "ERROR"){
				DB::table('pos_pull')->where('st_document_number', $request->st_number)->update([
					'status' => 'FOR RECEIVING',
					'received_st_number' => $st_number,
					'received_st_date' => date('Y-m-d')
				]);

				$record = true;
			}
			foreach ($request->digits_code as $key_item => $value_item) {
				app(EBSPushController::class)->createSIT($st_number, 
					$request->bea_item_id[$key_item], 
					$request->st_quantity[$key_item], 
					$request->from_transfer_subinventory,
					$request->transfer_to_subinventory,
					''
				);
			}
			
			if($record)
                CRUDBooster::redirect(CRUDBooster::adminpath('sts_pick_confirm'),'ST# '.$request->st_number.' received successfully!','success')->send();
            else{
                CRUDBooster::redirect(CRUDBooster::adminpath('sts_pick_confirm'),$postedST['data']['record']['errors']['error'].'. ST# '.$request->st_number.' failed to received!','danger')->send();
            }
		}*/
		
		public function saveSTSReceiving(Request $request)
		{
		    $isGisSt = DB::table('pos_pull_headers')->where('st_document_number',$request->st_number)->first();
			if(!$isGisSt->location_id_from){
				$posItemDetails = array();
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

				$refcode = $request->st_number;
				$transfer_dest = (empty($request->transfer_to_rma)) ? $request->transfer_to : $request->transfer_to_rma;
				$transfer_branch = (empty($request->from_transfer_rma_branch)) ? $request->from_transfer_branch : $request->from_transfer_rma_branch;
				$transfer_origin = (empty($request->from_transfer_rma)) ? $request->from_transfer_transit : $request->from_transfer_rma;
				
				$stockTransfers = app(POSPullController::class)->getStockTransferByRef($request->st_number); //check if already posted
				$record = false;
				if($stockTransfers['data']['record']['fstatus_flag'] != 'POSTED'){
					$postedST = app(POSPushController::class)->posCreateStockTransfer($refcode, $transfer_branch, $transfer_origin, $transfer_dest, 'STS', $posItemDetails);
					\Log::info('sts create rcv ST: '.json_encode($postedST));
					$record = false;
					$st_number = $postedST['data']['record']['fdocument_no'];
					if($postedST['data']['record']['fresult'] != "ERROR"){
						DB::table('pos_pull_headers')->where('st_document_number', $request->st_number)->update([
							'status' => self::Received,
							'received_st_number' => $st_number,
							'received_st_date' => date('Y-m-d'),
							'updated_at' => date('Y-m-d H:i:s')
						]);
		
						$record = true;
					}
				}
				
				if($record)
				{
					CRUDBooster::insertLog(trans("crudbooster.sts_received", ['ref_number' =>$request->st_number]));
					CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' received successfully!','success')->send();
				}
				else{
					CRUDBooster::redirect(CRUDBooster::mainpath(),$postedST['data']['record']['errors']['error'].'. ST# '.$request->st_number.' failed to be received!','danger')->send();
				}
			}else{
				$items = DB::table('pos_pull')->where('pos_pull_header_id',$isGisSt->id)->get();
				$to_intransit_gis_sub_location = DB::connection('gis')->table('sub_locations')->where('status','ACTIVE')
					->where('location_id',$isGisSt->location_id_to)->where('description','LIKE', '%'.'IN TRANSIT'.'%')->first();
				
				$from_intransit_gis_sub_location = DB::connection('gis')->table('sub_locations')->where('status','ACTIVE')
					->where('location_id',$isGisSt->location_id_from)->where('description','LIKE', '%'.'IN TRANSIT'.'%')->first();
				
					DB::table('pos_pull_headers')->where('st_document_number', $request->st_number)->update([
					'status'      => self::Received,
					'received_by' => CRUDBooster::myId(),
					'received_at' => date('Y-m-d H:i:s'),
					'updated_at'  => date('Y-m-d H:i:s')
				]);
				//ADD QTY IN GIS INVENTORY LINES TO LOCATION
				foreach($items as $key => $item){
					$item_master = DB::connection('gis')->table('items')->where('digits_code',$item->item_code)->first();
					$isToLocationExist = DB::connection('gis')->table('inventory_capsules')
					->leftjoin('inventory_capsule_lines','inventory_capsules.id','inventory_capsule_lines.inventory_capsules_id')
					->leftjoin('items','inventory_capsules.item_code','items.digits_code2')
					->where([
						'items.digits_code' => $item->item_code,
						'inventory_capsules.locations_id' => $isGisSt->location_id_to
					])
					->where('inventory_capsule_lines.sub_locations_id',$isGisSt->sub_location_id_to)
					->exists();
					if(!$isToLocationExist){
						$capsules = DB::connection('gis')->table('inventory_capsules')->insertGetId([
							'item_code' => $item_master->digits_code2,
							'locations_id' => $isGisSt->location_id_to
						]);
						
						DB::connection('gis')->table('inventory_capsule_lines')->insert([
							'inventory_capsules_id' => $capsules,
							'sub_locations_id' => $isGisSt->sub_location_id_to,
							'qty' => $item->quantity,
							'created_at' => date('Y-m-d H:i:s')
						]);
					}else{
						DB::connection('gis')->table('inventory_capsules')
						->leftjoin('inventory_capsule_lines','inventory_capsules.id','inventory_capsule_lines.inventory_capsules_id')
						->leftjoin('items','inventory_capsules.item_code','items.digits_code2')
						->where([
							'items.digits_code' => $item->item_code,
							'inventory_capsules.locations_id' => $isGisSt->location_id_to
						])
						->where('inventory_capsule_lines.sub_locations_id',$isGisSt->sub_location_id_to)
						->update([
							'qty' => DB::raw("qty + $item->quantity"),
							'inventory_capsule_lines.updated_at' => date('Y-m-d H:i:s')
						]);
					}

					//REMOVE IN FROM INTRANSIT
					DB::connection('gis')->table('inventory_capsules')
					->leftjoin('inventory_capsule_lines','inventory_capsules.id','inventory_capsule_lines.inventory_capsules_id')
					->leftjoin('items','inventory_capsules.item_code','items.digits_code2')
					->where([
						'items.digits_code' => $item->item_code,
						'inventory_capsules.locations_id' => $isGisSt->location_id_from
					])
					->where('inventory_capsule_lines.sub_locations_id',$from_intransit_gis_sub_location->id)
					->update([
						'inventory_capsule_lines.qty' => DB::raw("inventory_capsule_lines.qty - $item->quantity"),
						'inventory_capsule_lines.updated_at' => date('Y-m-d H:i:s')
					]);
					
					//ADD GIS MOVEMENT HISTORY
					//get item code
					$gis_mw_name = DB::connection('gis')->table('cms_users')->where('email','mw@gashapon.ph')->first();
					$item_code = DB::connection('gis')->table('items')->where('digits_code',$item->item_code)->first();
					$capsuleAction = DB::connection('gis')->table('capsule_action_types')->where('status','ACTIVE')
					->where('description','STOCK TRANSFER')->first();
					DB::connection('gis')->table('history_capsules')->insert([
						'reference_number' => $request->st_number,
						'item_code' => $item_code->digits_code2,
						'capsule_action_types_id' => $capsuleAction->id,
						'locations_id' => $isGisSt->location_id_to,
						'from_sub_locations_id' => $isGisSt->sub_location_id_to,
						'to_sub_locations_id' => $from_intransit_gis_sub_location->id,
						'qty' =>$item->quantity,
						'created_at' => date('Y-m-d H:i:s'),
						'created_by' => $gis_mw_name->id
					]);
					DB::connection('gis')->table('history_capsules')->insert([
						'reference_number' => $request->st_number,
						'item_code' => $item_code->digits_code2,
						'capsule_action_types_id' => $capsuleAction->id,
						'locations_id' => $isGisSt->location_id_to,
						'from_sub_locations_id' => $from_intransit_gis_sub_location->id,
						'to_sub_locations_id' => $isGisSt->sub_location_id_to,
						'qty' => -1 * abs($item->quantity),
						'created_at' => date('Y-m-d H:i:s'),
						'created_by' => $gis_mw_name->id
					]);
				}
				CRUDBooster::insertLog(trans("crudbooster.sts_received", ['ref_number' =>$request->st_number]));
				CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' received successfully!','success')->send();
			}
		}

		public function saveSTSOnlineReceiving(Request $request)
		{
			$record = false;

			$record = DB::table('pos_pull')->where('st_document_number', $request->st_number)->update([
				'status' => 'RECEIVED',
				'updated_at' => date('Y-m-d H:i:s')
			]);

			if($record){
			    CRUDBooster::insertLog(trans("crudbooster.sts_received", ['ref_number' =>$request->st_number]));
                CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' received successfully!','success')->send();
			}
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),$postedST['data']['record']['errors']['error'].'. ST# '.$request->st_number.' failed to received!','danger')->send();
            }
		}

		public function saveSTSOnlinePickConfirm(Request $request)
		{
			$posItemDetails = array();

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

			$refcode = $request->st_number;
			$postedST = app(POSPushController::class)->posCreateStockTransfer($refcode, $request->from_transfer_branch, $request->from_transfer_transit, $request->transfer_to, 'STS-'.$request->customer_name, $posItemDetails);
			\Log::info('sts create rcv ST: '.json_encode($postedST));
			$record = false;
			$st_number = $postedST['data']['record']['fdocument_no'];
			if($postedST['data']['record']['fresult'] != "ERROR"){
				DB::table('pos_pull')->where('st_document_number', $request->st_number)->update([
					'status' => 'FOR RECEIVING',
					'received_st_number' => $st_number,
					'received_st_date' => date('Y-m-d')
				]);

				$record = true;
			}
			
			if($request->from_transfer_subinventory != $request->transfer_to_subinventory){
    			foreach ($request->digits_code as $key_item => $value_item) {
    				app(EBSPushController::class)->createSIT($st_number, 
    					$request->bea_item_id[$key_item], 
    					$request->st_quantity[$key_item], 
    					$request->from_transfer_subinventory,
    					$request->transfer_to_subinventory,
    					''
    				);
    			}
			}
			
			if($record){
			    CRUDBooster::insertLog(trans("crudbooster.sts_pick_confirm", ['ref_number' =>$request->st_number]));
                CRUDBooster::redirect(CRUDBooster::adminpath('sts_pick_confirm'),'ST# '.$request->st_number.' received successfully!','success')->send();
			}
            else{
                CRUDBooster::redirect(CRUDBooster::adminpath('sts_pick_confirm'),$postedST['data']['record']['errors']['error'].'. ST# '.$request->st_number.' failed to received!','danger')->send();
            }
		}
	}
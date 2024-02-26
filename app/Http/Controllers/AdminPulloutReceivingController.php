<?php namespace App\Http\Controllers;

	use Session;
	use DB;
	use CRUDBooster;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\File;
	use Illuminate\Http\Request;

	class AdminPulloutReceivingController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,asc";
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
			$this->col[] = ["label"=>"ST #","name"=>"st_document_number"];
			$this->col[] = ["label"=>"MOR/SOR #","name"=>"sor_number"];
			$this->col[] = ["label"=>"From WH","name"=>"stores_id","join"=>"stores,pos_warehouse_name"];
			$this->col[] = ["label"=>"To WH","name"=>"wh_to"];
			$this->col[] = ["label"=>"Channel","name"=>"channel_id","join"=>"channel,channel_description"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
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
			$this->addaction[] = ['title'=>'Receive Pullout','url'=>CRUDBooster::mainpath().'/[st_document_number]','icon'=>'fa fa-cube','color'=>'info','showIf'=>"[status]=='FOR RECEIVING' and [channel_id]!='4'"];
			$this->addaction[] = ['title'=>'Receive Pullout','url'=>CRUDBooster::mainpath().'/[st_document_number]','icon'=>'fa fa-cube','color'=>'info','showIf'=>"[status]=='FOR RECEIVING' and [channel_id]=='4' and substr([wh_from], -3)!='FBV'"];
			$this->addaction[] = ['title'=>'Receive Pullout','url'=>CRUDBooster::mainpath().'/online/[st_document_number]','icon'=>'fa fa-cube','color'=>'info','showIf'=>"[status]=='FOR RECEIVING' and [channel_id]=='4' and substr([wh_from], -3)=='FBV'"];
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
	        //Your code here
	        if(!CRUDBooster::isSuperadmin()){
				$store = DB::table('stores')->where('id', CRUDBooster::myStore())->first();
				$query->select('st_document_number','wh_from','wh_to','channel.channel_description','pullout.status')
					->where('pullout.wh_to',$store->pos_warehouse)
					->whereNotNull('sor_number')
					->where('pullout.status','FOR RECEIVING')->distinct();   
			} 
			else{
			    $query->where('pullout.status','FOR RECEIVING')->distinct();
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
			$item_data = array();
			$stw_items = array();
			
			$data['page_title'] = 'Receive Pullout';

			$data['stDetails'] = DB::table('pullout')->join('reason', 'pullout.reason_id', '=', 'reason.bea_mo_reason')
				->join('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
				->where('st_document_number', $st_number)
				->select('pullout.*','reason.pullout_reason','transport_types.transport_type')
				->get();

			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id)->first();

			$data['transfer_to'] = DB::table('stores')->where('pos_warehouse',$data['stDetails'][0]->wh_to)->first();

			$items = DB::table('pullout')
			->where('st_document_number',$st_number)
			->select('id','item_code','quantity')
			->get();

			$data['stQuantity'] =  DB::table('pullout')
			->where('st_document_number', $st_number)
			->sum('quantity');

			foreach ($items as $key => $value) {
			    
                $stw_items[$value->item_code] = $value->quantity;
                
				$serials = DB::table('serials')->where('pullout_id',$value->id)->select('serial_number')->get();
				$item_detail = DB::table('items')->where('digits_code', $value->item_code)->first();

				$serial_data = array();
				foreach ($serials as $serial) {
					array_push($serial_data, $serial->serial_number);
				}

				$item_data[$key] = [
					'digits_code' => $value->item_code,
					'upc_code' => $item_detail->upc_code,
					'bea_item_id' => $item_detail->bea_item_id,
					'item_description' => $item_detail->item_description,
					'price' => $item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;
			$data['pullout_items'] = json_encode($stw_items);
// 			dd($data);
			if($data['stDetails'][0]->wh_to == 'DIGITSWAREHOUSE'){
				$this->cbView("pullout.receive-stw", $data);
			}
			else{
				$this->cbView("pullout.receive-rma", $data);
			}
			
		}

		public function getReceivingOnline($st_number)
		{
			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'Receive Pullout';

			$data['stDetails'] = DB::table('pullout')->where('st_document_number', $st_number)->get();

			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id)->first();

			$data['transfer_to'] = DB::table('stores')->where('pos_warehouse',$data['stDetails'][0]->wh_to)->first();

			$items = DB::table('pullout')
			->where('st_document_number',$st_number)
			->select('id','item_code','quantity')
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
					'bea_item_id' => $item_detail->bea_item_id,
					'item_description' => $item_detail->item_description,
					'price' => $item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;
			if($data['stDetails'][0]->wh_to == 'DIGITSWAREHOUSE'){
				$this->cbView("pullout.receive-online-stw", $data);
			}
			else{
				$this->cbView("pullout.receive-online-rma", $data);
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
				->join('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
				->where('st_document_number', $st_number)
				->select('pullout.*','reason.pullout_reason','reason.id as reason_id','transport_types.transport_type')
				->get();
					
			if(count($data['stDetails']) == 0){
				$data['stDetails'] = DB::table('pullout')
					->join('reason', 'pullout.reason_id', '=', 'reason.bea_so_reason')
					->join('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
					->where('st_document_number', $st_number)
					->select('pullout.*','reason.pullout_reason','reason.id as reason_id','transport_types.transport_type')
					->get();
			}

			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id)->first();

			$data['transfer_to'] = DB::table('stores')->where('pos_warehouse',$data['stDetails'][0]->wh_to)->first();

			$items = DB::table('pullout')
			->where('st_document_number',$st_number)
			->select('id','item_code','quantity')
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
					'item_description' => $item_detail->item_description,
					'price' => $item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;
			
			$this->cbView("pullout.detail", $data);
		}

		public function itemSearch(Request $request)
		{
			$data = array();
            $data['status_no'] = 0;
            $data['message'] ='No item found!';
			
			$items = DB::table('pullout')
				->where('item_code', $request->item_code)
				->where('st_document_number', $request->st_number)
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

		public function serialSearch(Request $request)
		{
			$data = array();
            $data['status_no'] = 0;
			$data['message'] ='No item found!';
			
			$items = DB::table('pullout')
				->where('item_code', $request->item_code)
				->where('st_document_number', $request->st_number)
				->first();

			$serial = DB::table('serials')
				->where('pullout_id', $items->id)
				->whereRaw("BINARY `serial_number`='{$request->serial_number}'")
				//->where('serial_number', $request->serial_number)
				->first();
					
			if($serial && $serial->counter == 0) {
				$data['status_no'] = 1;
				$data['message'] ='Serial found!';
				$return_data['digits_code'] = $items->item_code;
				$data['serials'] = $return_data;

				DB::table('serials')
					->where('pullout_id', $items->id)
					->whereRaw("BINARY `serial_number`='{$request->serial_number}'")
					//->where('serial_number', $request->serial_number)
					->update([
						'status' => 'RECEIVED',
						'counter' => ($serial->counter + 1)
					]);
			}
			            
            echo json_encode($data);
			exit;
		}

		public function resetSerialCounter(Request $request)
		{
			
			$items = DB::table('pullout')
				->where('st_document_number', $request->st_number)
				->where('has_serial',1)
				->get();

			foreach ($items as $item) {
				DB::table('serials')
					->where('pullout_id', $item->id)
					->update([
						'counter' => 0
					]);
			}

            echo json_encode($data['message'] = 'done');
            exit;
		}

		public function savePulloutSTWReceiving(Request $request)
		{
		    $pullout_record = DB::table('pullout')->where('st_document_number', $request->st_number)->first();
			if($pullout_record->status == 'RECEIVED'){
				CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' has already been received!','warning')->send();
			}
			$posItemDetails = array();
			$store = DB::table('stores')->where('pos_warehouse',$request->transfer_from)->first();
			
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
			
			$postedST = app(POSPushController::class)->posCreateStockTransfer('BEAPOSMW', $request->from_transfer_branch, $request->from_transfer_transit, $request->transfer_to, 'STW-'.$request->customer_name, $posItemDetails);
			//dd($postedST);
			$record = false;
			$received_st_number = $postedST['data']['record']['fdocument_no'];
			if($postedST['data']['record']['fresult'] != "ERROR" && !empty($received_st_number)){
				DB::table('pullout')->where('st_document_number', $request->st_number)->update([
					'status' => 'RECEIVED',
					'received_st_number' => $received_st_number,
					'received_st_date' => date('Y-m-d'),
					'updated_at' => date('Y-m-d H:i:s'),
					'received_by' => CRUDBooster::myId(),
					'received_at' => date('Y-m-d H:i:s')
				]);

				$record = true;
				try {
					$sorDetails = app(EBSPullController::class)->getSORDetails($request->sor_number);
				} catch (\Exception $e) {
					\Log::error('SOR Detail Error: '.json_encode($e));
				}
				
				if($store->org_subinventory == 'RETAIL'){
					app(EBSPushController::class)->dtoReceiving($request->st_number, 224);
				}

				elseif($store->channel_id == 4 && substr($store->bea_mo_store_name, -3) == 'FBV'){
					app(EBSPushController::class)->dtoReceiving($request->st_number, 263);
				}

				elseif($store->channel_id == 4 && substr($store->bea_mo_store_name, -3) == 'FBD'){
					// $sorHeader = app(EBSPushController::class)->sorReceiveHeaders($sorDetails[0]->customer_id);
					// foreach ($sorDetails as $key_item => $value_item) {
						
					// 	app(EBSPushController::class)->sorReceiveLines(
					// 		$request->st_number, 
					// 		$value_item->subinventory, 
					// 		$value_item->inventory_item_id, 
					// 		$value_item->ordered_quantity, 
					// 		$value_item->header_id, 
					// 		$value_item->line_id, 
					// 		$value_item->customer_id, 
					// 		$value_item->customer_site_id,
					// 		$sorHeader['sor_rcv_header'],
					// 		$sorHeader['sor_rcv_group_nextval'],
					// 		263);
					// }
					foreach ($request->digits_code as $key_item => $value_item) {
						app(EBSPushController::class)->createSIT($received_st_number, 
							$request->bea_item_id[$key_item], 
							$request->st_quantity[$key_item], 
							$request->from_transfer_subinventory,
							'ECOM',
							''
						);
					}
				}
				
				elseif($store->org_subinventory == 'FRANCHISE'){ //&& $request->transaction_type == 'STW'
					
					if(substr($request->digits_code[0], 0 , 1) == '7'){
						app(EBSPushController::class)->dtoReceiving($request->st_number, 224);
					}
					else{
						$sorHeader = app(EBSPushController::class)->sorReceiveHeaders($sorDetails[0]->customer_id);
						foreach ($sorDetails as $key_item => $value_item) {
							
							app(EBSPushController::class)->sorReceiveLines(
								$request->st_number, 
								$value_item->subinventory, 
								$value_item->inventory_item_id, 
								$value_item->ordered_quantity, 
								$value_item->header_id, 
								$value_item->line_id, 
								$value_item->customer_id, 
								$value_item->customer_site_id,
								$sorHeader['sor_rcv_header'],
								$sorHeader['sor_rcv_group_nextval'],
								224);
						}
					}
					
				}
				
			}

			if($record && !empty($received_st_number))
                CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' received successfully!','success')->send();
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),$postedST['data']['record']['errors']['error'].'. ST# '.$request->st_number.' failed to be received!','danger')->send();
            }
		}

		public function savePulloutRMAReceiving(Request $request)
		{
		    
		    $pullout_record = DB::table('pullout')->where('st_document_number', $request->st_number)->first();
			if($pullout_record->status == 'RECEIVED'){
				CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' has already been received!','warning')->send();
			}
			$posItemDetails = array();
			$store = DB::table('stores')->where('pos_warehouse',$request->transfer_from)->first();

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

			$postedST = app(POSPushController::class)->posCreateStockTransfer('BEAPOSMW', $request->from_transfer_branch, $request->from_transfer_rma, $request->transfer_to, 'STR-'.$request->customer_name, $posItemDetails);
			//dd($postedST);
			$record = false;
			$received_st_number = $postedST['data']['record']['fdocument_no'];
			if($postedST['data']['record']['fresult'] != "ERROR" && !empty($received_st_number)){
				DB::table('pullout')->where('st_document_number', $request->st_number)->update([
					'status' => 'RECEIVED',
					'received_st_number' => $received_st_number,
					'received_st_date' => date('Y-m-d'),
					'updated_at' => date('Y-m-d H:i:s'),
					'received_by' => CRUDBooster::myId(),
					'received_at' => date('Y-m-d H:i:s')
				]);

				$record = true;
				try {
					$sorDetails = app(EBSPullController::class)->getSORDetails($request->sor_number);
				} catch (\Exception $e) {
					\Log::error('SOR Detail Error: '.json_encode($e));
				}

				if($store->org_subinventory == 'RETAIL'){
					app(EBSPushController::class)->dtoReceiving($request->st_number, 225);
				}

				// elseif($store->channel_id == 4 && substr($store->bea_mo_store_name, -3) == 'FBV'){
				// 	app(EBSPushController::class)->dtoReceiving($request->st_number, 225);
				// }
				
				elseif($store->channel_id == 4 ){ //&& substr($store->bea_mo_store_name, -3) == 'FBD'
					$sorRMAHeader = app(EBSPushController::class)->sorReceiveHeaders($sorDetails[0]->customer_id);
					foreach ($sorDetails as $key_item => $value_item) {
						
						app(EBSPushController::class)->sorRMAReceiveLines(
							$request->st_number, 
							$value_item->subinventory, 
							$value_item->inventory_item_id, 
							$value_item->ordered_quantity, 
							$value_item->header_id, 
							$value_item->line_id, 
							$value_item->customer_id, 
							$value_item->customer_site_id,
							$sorRMAHeader['sor_rcv_header'],
							$sorRMAHeader['sor_rcv_group_nextval']);
					}
				}

				elseif($store->org_subinventory == 'FRANCHISE'){ //fra && $request->transaction_type == 'RMA'

					$sorRMAHeader = app(EBSPushController::class)->sorReceiveHeaders($sorDetails[0]->customer_id);
					foreach ($sorDetails as $key_item => $value_item) {
						
						app(EBSPushController::class)->sorRMAReceiveLines(
							$request->st_number, 
							$value_item->subinventory, 
							$value_item->inventory_item_id, 
							$value_item->ordered_quantity, 
							$value_item->header_id, 
							$value_item->line_id, 
							$value_item->customer_id, 
							$value_item->customer_site_id,
							$sorRMAHeader['sor_rcv_header'],
							$sorRMAHeader['sor_rcv_group_nextval']);
					}
				}
			}

			if($record && !empty($received_st_number))
                CRUDBooster::redirect(CRUDBooster::mainpath(),'ST# '.$request->st_number.' received successfully!','success')->send();
            else{
                CRUDBooster::redirect(CRUDBooster::mainpath(),$postedST['data']['record']['errors']['error'].'. ST# '.$request->st_number.' failed to be received!','danger')->send();
            }
		}

		public function closeReceiving() //$datefrom, $dateto
		{
			$datefrom = date("Y-m-d");
        	$dateto = date("Y-m-d", strtotime("+1 day"));

			$mor = app(EBSPullController::class)->getMOR($datefrom, $dateto);
			$sor = app(EBSPullController::class)->getSOR($datefrom, $dateto);
			$record = false;
			$receivedST = array();
			//$posItemDetails = array();

			if($mor->isNotEmpty()) { 
				//close mw
				
				//create st transit->warehouse
				foreach ($mor as $key => $value) {
					//query pullout data
					$pulloutMOR = DB::table('pullout')->where('st_document_number', $value->mor_number)->first();

					if(!in_array($value->mor_number, $receivedST) && $pulloutMOR->status == 'FOR RECEIVING'){
						array_push($receivedST, $value->mor_number);
						$pulloutDT = self::getPulloutDetail($value->mor_number);

						$postedST = app(POSPushController::class)->posCreateStockTransfer('BEAPOSMW', $pulloutDT['transfer_branch'], $pulloutDT['transfer_from'], $pulloutDT['transfer_to'], $pulloutDT['transaction_type'].' FROM ST:'.$value->mor_number, $pulloutDT['posItemDetails']);
						\Log::info(json_encode($postedST));
						$st_number = $postedST['data']['record']['fdocument_no'];
						if($postedST['data']['record']['fresult'] != "ERROR"){
							DB::table('pullout')->where('st_document_number', $value->mor_number)->update([
								'status' => 'RECEIVED',
								'received_st_number' => $st_number,
								'received_st_date' => date('Y-m-d'),
								'updated_at' => date('Y-m-d H:i:s')
							]);
						}
						$record = true;
					}
				}
			}
			if($sor->isNotEmpty()) { 
				foreach ($sor as $key => $value) {
					//query pullout data
					$pulloutSOR = DB::table('pullout')->where('st_document_number', $value->customer_po_number)->first();

					if(!in_array($value->customer_po_number, $receivedST) && $pulloutSOR->status == 'FOR RECEIVING'){
						array_push($receivedST, $value->customer_po_number);
						$pulloutDT = self::getPulloutDetail($value->customer_po_number);

						$postedST = app(POSPushController::class)->posCreateStockTransfer('BEAPOSMW', $pulloutDT['transfer_branch'], $pulloutDT['transfer_from'], $pulloutDT['transfer_to'], $pulloutDT['transaction_type'].' FROM ST:'.$value->customer_po_number, $pulloutDT['posItemDetails']);
						\Log::info(json_encode($postedST));
						$st_number = $postedST['data']['record']['fdocument_no'];
						if($postedST['data']['record']['fresult'] != "ERROR"){
							DB::table('pullout')->where('st_document_number', $value->customer_po_number)->update([
								'status' => 'RECEIVED',
								'received_st_number' => $st_number,
								'received_st_date' => date('Y-m-d'),
								'updated_at' => date('Y-m-d H:i:s')
							]);
						}
						$record = true;
					}
				}
			}
			if($record){
				CRUDBooster::redirect(CRUDBooster::mainpath(),'Pullout received in BEA successfully!','success')->send();
			}
			else{
				CRUDBooster::redirect(CRUDBooster::mainpath(),'No pullout received in BEA!','info')->send();
			}
		}

		public function getPulloutDetail($st_number)
		{
			$data = array();
			$posItemDetails = array();
			$pulloutDetails = DB::table('pullout')->where('st_document_number',$st_number)->get();

			foreach ($pulloutDetails as $key_pullout => $value_pullout) {
				$price = DB::table('items')->where('digits_code', $value_pullout->item_code)->value('store_cost');
				if(!empty($value_pullout->serial)){
					$serials = DB::table('serials')->where('pullout_id', $value_pullout->id)->get();
					
					foreach ($serials as $key_serial => $value_serial) {
						
						$posItemDetails[$value_pullout->item_code.'-'.$key_serial] = [
							'item_code' => $value_pullout->item_code,
							'quantity' => 1,
							'serial_number' => $value_serial->serial_number,
							'item_price' => $price
						];
					}
					
				}
				else{
					
					$posItemDetails[$value_pullout->item_code.'-'.$key_pullout] = [
						'item_code' => $value_pullout->item_code,
						'quantity' => $value_pullout->quantity,
						'item_price' => $price
					];
					
				}
			}
			$pullout = DB::table('pullout')->where('st_document_number',$st_number)->first();
			$transfer_from = DB::table('stores')->where('pos_warehouse',$pullout->wh_from)->first();
			
			$data['posItemDetails'] = $posItemDetails;
			$data['transfer_branch'] = $transfer_from->pos_warehouse_branch;
			$data['transfer_from'] = ($pullout->transaction_type == "STW") ? $transfer_from->pos_warehouse_transit : $transfer_from->pos_warehouse_rma;
			$data['transfer_to'] = $pullout->wh_to;
			$data['transaction_type'] = $pullout->transaction_type;
			
			return $data;
		}

		public function getPulloutDetailNull()
		{
			$data = array();
			$posItemDetails = array();
			$pulloutDetails = DB::table('pullout')->whereNull('st_document_number')->get();

			foreach ($pulloutDetails as $key_pullout => $value_pullout) {
				$price = DB::table('items')->where('digits_code', $value_pullout->item_code)->value('store_cost');
				if(!empty($value_pullout->serial)){
					$serials = DB::table('serials')->where('pullout_id', $value_pullout->id)->get();
					
					foreach ($serials as $key_serial => $value_serial) {
						
						$posItemDetails[$value_pullout->item_code.'-'.$key_serial] = [
							'item_code' => $value_pullout->item_code,
							'quantity' => 1,
							'serial_number' => $value_serial->serial_number,
							'item_price' => $price
						];
					}
					
				}
				else{
					
					$posItemDetails[$value_pullout->item_code.'-'.$key_pullout] = [
						'item_code' => $value_pullout->item_code,
						'quantity' => $value_pullout->quantity,
						'item_price' => $price
					];
					
				}
			}
			$pullout = DB::table('pullout')->whereNull('st_document_number')->first();
			$transfer_from = DB::table('stores')->where('pos_warehouse',$pullout->wh_from)->first();
			
			$data['posItemDetails'] = $posItemDetails;
			$data['transfer_branch'] = $transfer_from->pos_warehouse_branch;
			$data['transfer_from'] = $pullout->wh_from;
			$data['transfer_to'] = ($pullout->transaction_type == "STW") ? $transfer_from->pos_warehouse_transit : $transfer_from->pos_warehouse_rma;
			$data['transaction_type'] = $pullout->transaction_type;
			
			return $data;
		}

		public function createReceivedST($st_number)
		{
			$record = false;
			$pulloutDT = self::getPulloutDetail($st_number);

			$postedST = app(POSPushController::class)->posCreateStockTransfer('BEAPOSMW', 
				$pulloutDT['transfer_branch'], 
				$pulloutDT['transfer_from'], 
				$pulloutDT['transfer_to'], 
				$pulloutDT['transaction_type'].' FROM ST:'.$st_number, 
				$pulloutDT['posItemDetails']);
			
			$pos_st_number = $postedST['data']['record']['fdocument_no'];
			if($postedST['data']['record']['fresult'] != "ERROR"){
				DB::table('pullout')->where('st_document_number', $st_number)->update([
					'status' => 'RECEIVED',
					'received_st_number' => $pos_st_number,
					'received_st_date' => date('Y-m-d'),
					'updated_at' => date('Y-m-d H:i:s')
				]);
				$record = true;
			}
			
			if($record){
				CRUDBooster::redirect(CRUDBooster::mainpath(),'Pullout received in POS successfully!','success')->send();
			}
			else{
				CRUDBooster::redirect(CRUDBooster::mainpath(),'No pullout received in POS!','info')->send();
			}
		}

		public function createST()
		{
			$record = false;
			$pulloutDT = self::getPulloutDetailNull();

			$postedST = app(POSPushController::class)->posCreateStockTransfer('BEAPOSMW', 
				$pulloutDT['transfer_branch'], 
				$pulloutDT['transfer_from'], 
				$pulloutDT['transfer_to'], 
				$pulloutDT['transaction_type'], 
				$pulloutDT['posItemDetails']);
			
			$pos_st_number = $postedST['data']['record']['fdocument_no'];
			if($postedST['data']['record']['fresult'] != "ERROR"){
				DB::table('pullout')->whereNull('st_document_number')->update([
					'st_document_number' => $pos_st_number,
					'updated_at' => date('Y-m-d H:i:s')
				]);
				$record = true;
			}
			
			if($record){
				CRUDBooster::redirect(CRUDBooster::mainpath(),'Pullout created in POS successfully!','success')->send();
			}
			else{
				CRUDBooster::redirect(CRUDBooster::mainpath(),'No pullout created in POS!','info')->send();
			}
		}
	}
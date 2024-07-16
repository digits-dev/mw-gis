<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminPosTransactionsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "st_document_number";
			$this->limit = "20";
			$this->orderby = "received_st_date,desc";
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
			$this->button_export = true;
			$this->table = "pos_pull";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Created Date","name"=>"created_date"];
			$this->col[] = ["label"=>"ST#","name"=>"st_document_number"];
			$this->col[] = ["label"=>"Received ST#","name"=>"received_st_number"];
			$this->col[] = ["label"=>"Received Date","name"=>"received_st_date"];
			$this->col[] = ['label'=>'Status','name'=>'status'];
			$this->col[] = ["label"=>"WH From","name"=>"wh_from"];
			$this->col[] = ["label"=>"WH To","name"=>"wh_to"];
			$this->col[] = ["label"=>"Digits Code","name"=>"item_code"];
			$this->col[] = ['label'=>'Item Description','name'=>'item_description'];
			$this->col[] = ['label'=>'Qty','name'=>'quantity'];
			$this->col[] = ["label"=>"Memo","name"=>"memo"];
			$this->col[] = ['label'=>'Serial','name'=>'serial'];
			
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
            if(CRUDBooster::isSuperadmin()){
            	$this->button_selected[] = ['label'=>'Set Closed Status', 'icon'=>'fa fa-times-circle', 'name'=>'set_closed_status'];
            	$this->button_selected[] = ['label'=>'Set Received Status', 'icon'=>'fa fa-check-circle', 'name'=>'set_received_status'];
            	$this->button_selected[] = ['label'=>'Re-run ST Creation', 'icon'=>'fa fa-refresh', 'name'=>'rerun_st_creation'];
            	$this->button_selected[] = ['label'=>'Re-run ST Receiving', 'icon'=>'fa fa-refresh', 'name'=>'rerun_st_receiving'];
            	$this->button_selected[] = ['label'=>'Reset Serial Flag', 'icon'=>'fa fa-refresh', 'name'=>'reset_serial_flag'];
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
	        if($button_name == 'set_closed_status'){
				DB::table('pos_pull')->whereIn('id', $id_selected)->update([
					'status' => 'CLOSED'
				]);
			} 
			if($button_name == 'set_received_status'){
				DB::table('pos_pull')->whereIn('id', $id_selected)->update([
					'status' => 'RECEIVED'
				]);
			}
			if($button_name == 'rerun_st_creation'){
	            $posItemDetails = array();
				$stsDetails = DB::table('pos_pull')->whereIn('id', $id_selected)->get();
				foreach($stsDetails as $value){
				    $itemDetails = DB::table('items')->where('digits_code',$value->item_code)->first();
				    if($value->has_serial == 1){
				        $serials = explode(",",$value->serial);
				        foreach($serials as $serial){
				            $posItemDetails[$key.'-'.$serial] = [
    							'item_code' => $value->item_code,
    							'quantity' => 1,
    							'serial_number' => $serial,
    							'item_price' => $itemDetails->store_cost
    						];
				        }
				    }
				    else{
				        $posItemDetails[$value->item_code.'-'.$key] = [
    						'item_code' => $value->item_code,
    						'quantity' => $value->quantity,
    						'item_price' => $itemDetails->store_cost
    					];
				    }
				}

                $refcode = 'BEAPOSMW-STS-'.date('His');
                $whfrom = DB::table('stores')->where('pos_warehouse', $stsDetails[0]->wh_from)->first();
                $whto = DB::table('stores')->where('pos_warehouse', $stsDetails[0]->wh_to)->first();
                $reason = DB::table('reason')->where('id', $stsDetails[0]->reason_id)
                    ->where('transaction_type_id',4)->first();

                $transfer_destination = $whfrom->pos_warehouse_transit;

                if(str_contains($whto->bea_so_store_name, 'SERVICE CENTER') && !str_contains($reason->pullout_reason, 'REQUEST')){
                    $transfer_destination = $whfrom->pos_warehouse_rma;
                }
                
                $postedST = app(POSPushController::class)->posCreateStockTransfer($refcode, 
                    $whfrom->pos_warehouse_branch, 
                    $whfrom->pos_warehouse, 
                    $transfer_destination, 
                    $stsDetails[0]->memo, 
                    $posItemDetails);
                
                \Log::info('sts create ST: '.json_encode($postedST));
                //20210215 add checking if st number is not null
                $st_number = $postedST['data']['record']['fdocument_no'];
                DB::table('pos_pull')->whereIn('id',$id_selected)->update([
                    'st_document_number' => $st_number
                ]);

			} 

			if($button_name == 'reset_serial_flag'){
				\Log::info('---reset_serial_flag---');
				try {
					DB::table('serials')->whereIn('pos_pull_id', $id_selected)->update([
						'count' => 0,
						'status' => 'PENDING'
					]);
				} catch (\Exception $e) {
					\Log::error('Error!'.$e->getMessage());
				}
				\Log::info('---done resetting serial_flag---');
			}
			
			if($button_name == 'rerun_st_receiving'){
			    \Log::info('---rerun_st_receiving---');
			    $posItemDetails = array();
				$stsDetails = DB::table('pos_pull')->whereIn('id', $id_selected)->get();
				
				foreach($stsDetails as $key => $value){
				    $itemDetails = DB::table('items')->where('digits_code',$value->item_code)->first();
				    if($value->has_serial == 1){
				        $serials = explode(",",$value->serial);
				        foreach($serials as $serial){
				            $posItemDetails[$key.'-'.$serial] = [
    							'item_code' => $value->item_code,
    							'quantity' => 1,
    							'serial_number' => $serial,
    							'item_price' => $itemDetails->store_cost
    						];
				        }
				    }
				    else{
				        $posItemDetails[$value->item_code.'-'.$key] = [
    						'item_code' => $value->item_code,
    						'quantity' => $value->quantity,
    						'item_price' => $itemDetails->store_cost
    					];
				    }
				}

                $refcode = $stsDetails[0]->st_document_number;
                $receivedDate = $stsDetails[0]->received_st_date;
                $whfrom = DB::table('stores')->where('pos_warehouse', $stsDetails[0]->wh_from)->first();
                $whto = DB::table('stores')->where('pos_warehouse', $stsDetails[0]->wh_to)->first();
                $reason = DB::table('reason')->where('id', $stsDetails[0]->reason_id)
                    ->where('transaction_type_id',4)->first();

                $transfer_origin = $whfrom->pos_warehouse_transit;
                $transfer_branch = $whfrom->pos_warehouse_transit_branch;
                $transfer_destination = $whto->pos_warehouse;

                if(str_contains($whto->bea_so_store_name, 'SERVICE CENTER') && !str_contains($reason->pullout_reason, 'REQUEST')){
                    $transfer_origin = $whfrom->pos_warehouse_rma;
                    $transfer_branch = $whfrom->pos_warehouse_rma_branch;
                    $transfer_destination = $whto->pos_warehouse_rma;
                }
                \Log::info('---push to pos---');
                //app(POSPushController::class)
                $postedST = (new POSPushController)->posCreateStockTransferReceiving($refcode, 
                    $transfer_branch, 
                    $transfer_origin, 
                    $transfer_destination, 
                    "STS", 
                    date("Ymd", strtotime($receivedDate)),
                    $posItemDetails);
                
                \Log::info('sts create rcv ST: '.json_encode($postedST));
                //20210215 add checking if st number is not null
                $st_number = $postedST['data']['record']['fdocument_no'];
                DB::table('pos_pull')->whereIn('id',$id_selected)->update([
                    'received_st_number' => $st_number
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
				else if($column_value == "FOR PROCESSING"){
					$column_value = '<span class="label label-info">FOR PROCESSING</span>';
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


	}
<?php namespace App\Http\Controllers;

use App\CodeCounter;
	use Session;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\File;
	use Illuminate\Http\Request;
	use Illuminate\Support\Str;
	use DB;
	use CRUDBooster;
	use App\InvestigationReason;
	use App\Store;
	use App\Reason;
	use App\PulloutReceiving;
	use App\PulloutReceivingLine;
	use App\PulloutReceivingSerial;

	class AdminWrsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "wrs_number";
			$this->limit = "20";
			$this->orderby = "wrs_number,desc";
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
			$this->table = "pullout_receivings";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"WRS #","name"=>"wrs_number"];
			$this->col[] = ["label"=>"REF #","name"=>"ref_number"];
			$this->col[] = ["label"=>"SOR/MOR #","name"=>"sor_mor_number"];
			$this->col[] = ["label"=>"STORE","name"=>"stores_id","join"=>"stores,bea_so_store_name"];
			$this->col[] = ["label"=>"REASON","name"=>"reason_id","join"=>"reason,pullout_reason"];
			$this->col[] = ["label"=>"PULLOUT FROM","name"=>"pullout_from"];
			$this->col[] = ["label"=>"STATUS","name"=>"status"];
			$this->col[] = ["label"=>"CREATED BY","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"CREATED DATE","name"=>"created_at"];
			$this->col[] = ["label"=>"UPDATED BY","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"UPDATED DATE","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

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
            if (CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(),["Warehouse"])) {
                $this->addaction[] = [
            	'title' => 'WRS Details', 'url' => CRUDBooster::mainpath('details/[wrs_number]'),
            	'icon' => 'fa fa-eye', 'color' => 'warning'];
            	
                $this->addaction[] = [
            	'title' => 'Print WRS', 'url' => CRUDBooster::mainpath('print/[wrs_number]'),
            	'icon' => 'fa fa-print', 'color' => 'info'];
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
            if(CRUDBooster::getCurrentMethod() == 'getIndex'){
                if (CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(),["WH TL","WH TM"])) {
                    $this->index_button[] = ['label'=>'Receive','url'=>CRUDBooster::mainpath('receive'),'icon'=>'fa fa-plus','color'=>'success'];
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
	    
	    public function getReceive(){
            $data = array();
            $data['page_title'] = 'Receiving';

            $data['investigation_reasons'] = InvestigationReason::where('status','ACTIVE')
            ->select('id','investigation_reason')->get(); 

			$data['reasons'] = Reason::where('status','ACTIVE')
            ->select('id','pullout_reason')->orderBy('pullout_reason','ASC')->get(); 

			$data['stores'] = Store::where('status','ACTIVE')
            ->select('id','store_name')->orderBy('store_name','ASC')->get(); 

            // dd($data);
            return view('wrs.receive',$data);
	    }
	    
	    public function getStwDetails($st_number){
	        
	        $data = array();
			$dataUpc = array();
	        
	        $stwDetail = DB::connection('dms')->table('pullout')
	        ->where('st_document_number',$st_number)
	        ->where('wh_to','DIGITSWAREHOUSE')
	        ->where('status','FOR RECEIVING')
	        ->first();
	        
	        if(count((array)$stwDetail) <= 0){
	            return $data;
	        }
	        
	        if($stwDetail->channel_id == 1){
	            $data['stw'] = DB::connection('dms')->table('pullout')
        	        ->join('reason', 'pullout.reason_id', '=', 'reason.bea_mo_reason')
        	        ->join('stores', 'pullout.stores_id', '=', 'stores.id')
        	        ->where('pullout.st_document_number',$st_number)
        	        ->where('pullout.wh_to','DIGITSWAREHOUSE')
        	        ->where('pullout.status','FOR RECEIVING')
        	        ->select('pullout.st_document_number as ref_number',
            	        'pullout.sor_number',
            	        'stores.pos_warehouse_name as store_name',
            	        'stores.id as store_id',
            	        'reason.pullout_reason',
            	        'reason.id as reason_id')->distinct()->get();
    	    }
    	    else{
    	        $data['stw'] = DB::connection('dms')->table('pullout')
        	        ->join('reason', 'pullout.reason_id', '=', 'reason.bea_so_reason')
        	        ->join('stores', 'pullout.stores_id', '=', 'stores.id')
        	        ->where('pullout.st_document_number',$st_number)
        	        ->where('pullout.wh_to','DIGITSWAREHOUSE')
        	        ->where('pullout.status','FOR RECEIVING')
        	        ->select('pullout.st_document_number as ref_number',
            	        'pullout.sor_number',
            	        'stores.pos_warehouse_name as store_name',
            	        'stores.id as store_id',
            	        'reason.pullout_reason',
            	        'reason.id as reason_id')->distinct()->get();
    	    }
    	    
	        $data['items'] = DB::connection('dms')->table('pullout')
    	        ->where('st_document_number',$st_number)
    	        ->where('wh_to','DIGITSWAREHOUSE')
    	        ->where('status','FOR RECEIVING')
    	        ->select('item_code',
    	        'item_description',
    	        'quantity',
    	        'has_serial',
    	        'serial')->get();

			foreach ($data['items'] as $key => $value) {
				$pulloutItemDetails = DB::table('items')->where('digits_code',$value->item_code)->first();
				array_push($dataUpc,[
					'digits_code' => $pulloutItemDetails->digits_code,
					'upc_code' => $pulloutItemDetails->upc_code,
					'upc_code2' => $pulloutItemDetails->upc_code2,
					'upc_code3' => $pulloutItemDetails->upc_code3,
					'upc_code4' => $pulloutItemDetails->upc_code4,
					'upc_code5' => $pulloutItemDetails->upc_code5,
					'has_serial' => $pulloutItemDetails->has_serial
				]);
			}

			$data['items_upc'] = json_decode(json_encode($dataUpc), FALSE);
			
	        return $data;
	    }
	    
	    public function searchItem($item_code){
	        $data = array();
	        
	        $itemDetails = DB::table('items')->where('digits_code',$item_code)
	        ->select('digits_code','item_description','has_serial')->first();
	        
	        if(count((array)$itemDetails) <= 0){
	            return $data;
	        }
	        
	        $data['items'] = $itemDetails;
	        
	        return $data;
	    }
	    
	    public function getWrsDetail($wrs_number){
	        $data = array();
			$item_data = array();
			
			$data['page_title'] = 'WRS Details';

			$headerDetail = PulloutReceiving::where('wrs_number',$wrs_number)->first();

			$data['wrsHeader'] =  PulloutReceiving::where('wrs_number',$wrs_number)
				->join('reason','pullout_receivings.reason_id','=','reason.id')
				->leftJoin('stores','pullout_receivings.stores_id','=','stores.id')
				->select('pullout_receivings.*','reason.pullout_reason','stores.bea_so_store_name')
				->first();

			$data['totalQty'] =  PulloutReceivingLine::where('pullout_receivings_id',$headerDetail->id)->sum('quantity');

			$data['totalReceived'] =  PulloutReceivingLine::where('pullout_receivings_id',$headerDetail->id)->sum('received_quantity');

			$items = PulloutReceivingLine::where('pullout_receivings_id',$headerDetail->id)->get();
			$serial_data = array();
			foreach ($items as $key => $value) {

				$serials = PulloutReceivingSerial::where('pullout_receiving_lines_id',$value->id)->select('serial_number')->get();
				$item_detail = DB::table('items')->where('digits_code', $value->item_code)->first();
				
				foreach ($serials as $serial) {
					array_push($serial_data, $serial->serial_number);
				}

				$item_data[$key] = [
					'digits_code' => $value->item_code,
					'upc_code' => $item_detail->upc_code,
					'item_description' => $item_detail->item_description,
					'quantity' => $value->quantity,
					'received_quantity' => $value->received_quantity,
					'investigation_quantity' => $value->investigation_quantity,
					'barcode_quantity' => $value->barcode_quantity,
					'investigation_reasons' => $value->investigation_reasons,
					'serial_numbers' => $serial_data
				];
			}
			
			$data['items'] = $item_data;
			// dd($data);
			$this->cbView("wrs.detail", $data);
	    }
	    
	    public function getWrsPrint($wrs_number){
	        
	    }
	    
	    public function resetSerialCounter(Request $request){
			
	    }

		public function serialSearch(Request $request){
			
	    }

		public function saveReceive(Request $request){
			// dd($request->all());
			$data_header = array();
			$data_lines = array();
			$data_serials = array();

			$wrs_number = CodeCounter::where('id',1)->value('wrs_code');

			$data_header = [
				'wrs_number' => 'WRS-'.str_pad($wrs_number, 5, "0", STR_PAD_LEFT),
				'ref_number' => $request->ref_number,
				'sor_mor_number' => $request->sor_mor_number, 
				'stores_id' => ($request->exists('stores_id')) ? $request->stores_id: NULL,
				'pullout_from' => ($request->exists('pullout_from')) ? $request->pullout_from: NULL,
				'reason_id' => $request->reasons_id,
				'status' => 'FOR PRINT',
				'created_by' => CRUDBooster::myId(),
				'created_at' => date('Y-m-d H:i:s')
			];
			//save header
			$header_id = PulloutReceiving::insertGetId($data_header);

			foreach ($request->item_codes as $key_item_code => $value_item_code) {
				$itemSerials = $value_item_code.'_serial_number';
				$pulloutSerials = $value_item_code.'_pullout_serials';

				$data_lines = [
					'pullout_receivings_id' => $header_id,
					'item_code' => $value_item_code,
					'quantity' => $request->quantity[$key_item_code], 
					'received_quantity' => $request->received_quantity[$key_item_code],
					'investigation_quantity' => $request->investigation_quantity[$key_item_code],
					'barcode_quantity' => $request->barcode_quantity[$key_item_code],
					'investigation_reasons' => $request->investigation_reasons[$value_item_code],
					'investigation_reason_details' => $request->investigation_reason_details[$value_item_code],
					'created_at' => date('Y-m-d H:i:s')
				];

				if($request->exists($itemSerials)){
					unset($data_lines);
					$data_lines = [
						'pullout_receivings_id' => $header_id,
						'item_code' => $value_item_code,
						'quantity' => $request->quantity[$key_item_code], 
						'received_quantity' => $request->received_quantity[$key_item_code],
						'received_serial' => implode(",",$request->$itemSerials),
						'serial' => implode(",",$request->$pulloutSerials),
						'has_serial' => 1,
						'investigation_quantity' => $request->investigation_quantity[$key_item_code],
						'barcode_quantity' => $request->barcode_quantity[$key_item_code],
						'investigation_reasons' => $request->investigation_reasons[$value_item_code],
						'investigation_reason_details' => $request->investigation_reason_details[$value_item_code],
						'created_at' => date('Y-m-d H:i:s')
					];

					//save lines
					$lines_id = PulloutReceivingLine::insertGetId($data_lines);
					foreach ($request->$itemSerials as $key_item_serial => $value_item_serial) {
						$data_serials = [
							'pullout_receiving_lines_id' => $lines_id,
							'serial_number' => $value_item_serial,
							'status' => 'RECEIVED',
							'created_by' => CRUDBooster::myId(),
							'created_at' => date('Y-m-d H:i:s')
						];

						//save serials
						PulloutReceivingSerial::insert($data_serials);
					}
					
				}
				else{
					PulloutReceivingLine::insert($data_lines);
				}
			}

			CodeCounter::where('id',1)->increment('wrs_code');

			CRUDBooster::redirect(CRUDBooster::mainpath(),'Success! WRS# '.$wrs_number.' created!','success')->send();
			
		}
	    
	}
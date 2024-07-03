<?php namespace App\Http\Controllers;

use Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
use CRUDBooster;
use App\CodeCounter;
use App\TripTicket;
use App\TripTicketStatus;
use Excel;

class AdminTripTicketsController extends \crocodicstudio\crudbooster\controllers\CBController {

	public function cbInit() {

		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->title_field = "trip_number";
		$this->limit = "20";
		$this->orderby = "trip_number,desc";
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
		$this->table = "trip_tickets";
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = [];
		$this->col[] = ["label"=>"Trip Ticket #","name"=>"trip_number"];
		if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(),["LOG TL","LOG TM"])){
		    $this->col[] = ["label"=>"Trip Type","name"=>"trip_type"];
		    
		}
		if(!CRUDBooster::isSuperadmin() && !in_array(CRUDBooster::myPrivilegeName(),["LOG TL","LOG TM"])){
		    $this->col[] = ["label"=>"Trip Status","name"=>"trip_ticket_statuses_id","join"=>"trip_ticket_statuses,trip_status"];
		}
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
		if (CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(),["LOG TL","LOG TM"])) {
		    $this->addaction[] = [
			'title' => 'Details', 'url' => CRUDBooster::mainpath().'/details/[trip_number]?return_url='.urlencode(\Request::fullUrl()),
			'icon' => 'fa fa-eye', 'color' => 'primary'];
		}
		if (in_array(CRUDBooster::myPrivilegeName(),["Requestor","Gashapon Requestor","Store Head","Cashier"])) {
		    $this->addaction[] = [
			'title' => 'Details', 'url' => CRUDBooster::mainpath().'/store-details/[trip_number]?return_url='.urlencode(\Request::fullUrl()),
			'icon' => 'fa fa-eye', 'color' => 'primary'];
		}
		if (CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(),["LOG TL"])) {
            $this->addaction[] = [
			'title' => 'Update Trip Ticket', 'url' => CRUDBooster::mainpath().'/update-trip/[trip_number]?return_url='.urlencode(\Request::fullUrl()),
			'icon' => 'fa fa-pencil', 'color' => 'warning'];
		}
        if (CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(),["LOG TL","LOG TM"])) {
		    $this->addaction[] = [
			'title' => 'Print Trip Ticket', 'url' => CRUDBooster::mainpath('print-trip/[trip_number]'),
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
		    if (CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(),["LOG TL","LOG TM"])) {
            $this->index_button[] = ['label'=>'Create Outbound Trip','url'=>CRUDBooster::mainpath('create-trip-ticket-outbound'),'icon'=>'fa fa-plus','color'=>'success'];
            $this->index_button[] = ['label'=>'Create Inbound Trip','url'=>CRUDBooster::mainpath('create-trip-ticket-inbound'),'icon'=>'fa fa-plus','color'=>'primary'];
		    }
		    if (CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(),["Requestor","Gashapon Requestor","Store Head","Cashier"])) {
            $this->index_button[] = ['label'=>'Receive Delivery','url'=>CRUDBooster::mainpath('receive_outbound_trip_ticket'),'icon'=>'fa fa-cube','color'=>'success'];
            $this->index_button[] = ['label'=>'Release ST/TF','url'=>CRUDBooster::mainpath('release_inbound_trip_ticket'),'icon'=>'fa fa-cube','color'=>'primary'];
		    }
		    $this->index_button[] = ['label'=>'Export Trip Tricket','url'=>CRUDBooster::mainpath('export-trip-tickets').'?'.urldecode(http_build_query(@$_GET)),'icon'=>'fa fa-download','color'=>'primary'];
		    
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
		if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])){
		    $query->select('trip_number')->distinct();
		}
		else{
		    $query->select('trip_number')
		    ->whereIn('stores_id',CRUDBooster::myStore())->distinct();
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
	
	public function getTripTicketOutbound(){
        $data = array();
        $data['page_title'] = 'Create Outbound Trip Ticket';
        
        $pendingDR = DB::table('ebs_pull')->where('status','PENDING')
            ->select('stores_id')->distinct()->get()->toArray();
            
        $pendingSTS = DB::table('pos_pull_headers')->where('status','FOR RECEIVING')
            // ->where('scheduled_at',date('Y-m-d',strtotime("-1 days")))
            ->where('transport_types_id',1)
            ->select('stores_id_destination')->distinct()->get()->toArray();
            
        $data['stores'] = DB::table('stores')
        ->whereIn('channel_id',[1,2])
        ->where('status','ACTIVE')
        ->where(function ($subquery) use ($pendingDR, $pendingSTS) {
            $subquery->whereIn('id',array_column($pendingDR, 'stores_id'))
            ->orWhereIn('id',array_column($pendingSTS, 'stores_id_destination'));
        })->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name')
        ->orderBy('pos_warehouse_name','ASC')->get();
        
        return view('trip-ticket.create-outbound',$data);
    }
    
    public function getTripTicketInbound(){
        $data = array();
        $data['page_title'] = 'Create Inbound Trip Ticket';
        
        $pendingPullout = DB::table('pullout')->where('status','FOR RECEIVING')
            // ->where('schedule_date',date('Y-m-d'))
            ->where('transport_types_id',1)
            ->select('stores_id')->distinct()->get()->toArray();
            
        $pendingSTS = DB::table('pos_pull_headers')->where('status','FOR RECEIVING')
            // ->where('scheduled_at',date('Y-m-d'))
            ->where('transport_types_id',1)
            ->select('stores_id')->distinct()->get()->toArray();
            
        $data['stores'] = DB::table('stores')
        ->whereIn('channel_id',[1,2])
        ->where('status','ACTIVE')
        ->where(function ($subquery) use ($pendingPullout, $pendingSTS) {
            $subquery->whereIn('id',array_column($pendingPullout, 'stores_id'))
            ->orWhereIn('id',array_column($pendingSTS, 'stores_id'));
        })->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name')
        ->orderBy('pos_warehouse_name','ASC')->get();
        
        return view('trip-ticket.create-inbound',$data);
    }
    
    public function getPrintTripTicket($trip_number) {
		
		$data = [];
		$data['page_title'] = 'Print Trip Ticket';
		$data['items'] = DB::table('trip_tickets')
		->join('stores','trip_tickets.stores_id','=','stores.id')
		->select('trip_tickets.*','stores.pos_warehouse_name as route_name')
		->where('trip_tickets.trip_number',$trip_number)->get();

		$data['tripQty'] = DB::table('trip_tickets')
		->join('stores','trip_tickets.stores_id','=','stores.id')
			->select(DB::raw("SUM(trip_tickets.trip_qty) as drQty"),'stores.pos_warehouse_name as route_name')
			->where('trip_tickets.trip_number',$trip_number)
			->groupBy('trip_number','route_name')->get();

		$data['plasticQty'] = DB::table('trip_tickets')
		->join('stores','trip_tickets.stores_id','=','stores.id')
			->select(DB::raw("SUM(trip_tickets.plastic_qty) as plasticQty"),'stores.pos_warehouse_name as route_name')
			->where('trip_tickets.trip_number',$trip_number)
			->groupBy('trip_number','route_name')->get();

		$data['boxQty'] = DB::table('trip_tickets')
		->join('stores','trip_tickets.stores_id','=','stores.id')
			->select(DB::raw("SUM(trip_tickets.box_qty) as boxQty"),'stores.pos_warehouse_name as route_name')
			->where('trip_tickets.trip_number',$trip_number)
			->groupBy('trip_number','route_name')->get();
		
		$this->cbView('trip-ticket.print',$data);
	}
	
	public function getUpdateTripTicket($trip_number) {
		if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {    
			CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
		  }
		  
		  $data = [];
		  $data['page_title'] = 'Trip Ticket Update';
		  $data['items'] = DB::table('trip_tickets')
		  ->join('stores','trip_tickets.stores_id','=','stores.id')
		  ->leftJoin('backload_reasons','trip_tickets.backload_reasons_id','=','backload_reasons.id')
		  ->select('trip_tickets.*','backload_reasons.backload_reason','stores.pos_warehouse_name as route_name','stores.id as store_id')
		  ->where('trip_tickets.trip_number',$trip_number)->get();
		  
          $data['reasons'] = DB::table('backload_reasons')
          ->where('trip_type',$data['items'][0]->trip_type)
          ->where('status','ACTIVE')->get();
          
		  $data['drQty'] = DB::table('trip_tickets')
		  ->where('trip_tickets.trip_number',$trip_number)->sum('trip_qty');
  
		  $data['plasticQty'] = DB::table('trip_tickets')
		  ->where('trip_tickets.trip_number',$trip_number)->sum('plastic_qty');
  
		  $data['boxQty'] = DB::table('trip_tickets')
		  ->where('trip_tickets.trip_number',$trip_number)->sum('box_qty');
		  
		  $this->cbView('trip-ticket.update-trip',$data);
	}

	public function getDetail($trip_number) {
		if(!CRUDBooster::isRead() && $this->global_privilege==FALSE || $this->button_detail==FALSE) {    
		  CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
		}
		
		$data = [];
		$data['page_title'] = 'Trip Ticket Detail';
		$data['items'] = DB::table('trip_tickets')
		->join('stores','trip_tickets.stores_id','=','stores.id')
		->leftJoin('backload_reasons','trip_tickets.backload_reasons_id','=','backload_reasons.id')
		->select('trip_tickets.*','backload_reasons.backload_reason','stores.pos_warehouse_name as route_name')
		->where('trip_tickets.trip_number',$trip_number)->get();
		
		$data['drQty'] = DB::table('trip_tickets')
		->where('trip_tickets.trip_number',$trip_number)->sum('trip_qty');

		$data['plasticQty'] = DB::table('trip_tickets')
		->where('trip_tickets.trip_number',$trip_number)->sum('plastic_qty');

		$data['boxQty'] = DB::table('trip_tickets')
		->where('trip_tickets.trip_number',$trip_number)->sum('box_qty');
		
		$this->cbView('trip-ticket.detail',$data);
	}
	
	public function getTripStoreDetail($trip_number) {
		if(!CRUDBooster::isRead() && $this->global_privilege==FALSE || $this->button_detail==FALSE) {    
		  CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
		}
		
		$data = [];
		$data['page_title'] = 'Trip Ticket Detail';
		$data['items'] = DB::table('trip_tickets')
		->join('stores','trip_tickets.stores_id','=','stores.id')
		->leftJoin('backload_reasons','trip_tickets.backload_reasons_id','=','backload_reasons.id')
		->select('trip_tickets.*','backload_reasons.backload_reason','stores.pos_warehouse_name as route_name')
		->where('trip_tickets.trip_number',$trip_number)
		->whereIn('trip_tickets.stores_id',CRUDBooster::myStore())->get();
		
		$data['drQty'] = DB::table('trip_tickets')
		->where('trip_tickets.trip_number',$trip_number)
		->whereIn('trip_tickets.stores_id',CRUDBooster::myStore())
		->sum('trip_qty');

		$data['plasticQty'] = DB::table('trip_tickets')
		->where('trip_tickets.trip_number',$trip_number)
		->whereIn('trip_tickets.stores_id',CRUDBooster::myStore())
		->sum('plastic_qty');

		$data['boxQty'] = DB::table('trip_tickets')
		->where('trip_tickets.trip_number',$trip_number)
		->whereIn('trip_tickets.stores_id',CRUDBooster::myStore())
		->sum('box_qty');
		
		$this->cbView('trip-ticket.detail',$data);
	}
	
	public function getReceiveTripTicket(){
	    $data = array();
        $data['page_title'] = 'Receive Trip Ticket';
        
        $data['reasons'] = DB::table('backload_reasons')
          ->where('trip_type','OUT')
          ->where('status','ACTIVE')->get();
        
        if(CRUDBooster::isSuperadmin()){
			$data['routes'] = DB::table('stores')
			->where('status','ACTIVE')
			->whereIn('channel_id',[1,2])
			->select('id','pos_warehouse_name')
			->orderBy('pos_warehouse_name','ASC')->get();
		}
		else{
			$data['routes'] = DB::table('stores')
			->select('id','pos_warehouse_name')
			->where('status','ACTIVE')
			->whereIn('id',CRUDBooster::myStore())
			->orderBy('pos_warehouse_name','ASC')->get();
		}  
		
        return view('trip-ticket.receive-outbound',$data);
	}
	
	public function getReleaseTripTicket(){
	    $data = array();
        $data['page_title'] = 'Release Trip Ticket';
        $data['reasons'] = DB::table('backload_reasons')
          ->where('trip_type','IN')
          ->where('status','ACTIVE')->get();
        
        if(CRUDBooster::isSuperadmin()){
			$data['routes'] = DB::table('stores')
			->where('status','ACTIVE')
			->whereIn('channel_id',[1,2])
			->select('id','pos_warehouse_name')
			->orderBy('pos_warehouse_name','ASC')->get();
		}
		else{
			$data['routes'] = DB::table('stores')
			->select('id','pos_warehouse_name')
			->where('status','ACTIVE')
			->whereIn('id',CRUDBooster::myStore())
			->orderBy('pos_warehouse_name','ASC')->get();
		}
		
        return view('trip-ticket.release-inbound',$data);
	}
	
	public function updateTripTicket(Request $request) {
	    
	    foreach ($request->store_id as $key_store => $store_id) {
	       
	        $trips = 'trip'.$store_id;
	        $bReasons = 'backload_reasons'.$store_id;
	        $packagings = 'packaging'.$store_id;
	        
            // foreach($request->$bReasons as $key =>  $value){
            //     $data = array();
            //     if($value[0] == null){
            //         if($request->trip_type != 'IN'){
            //             $data['is_received'] = 1;
            //             $data['received_at'] = date('Y-m-d H:i:s');
            //         }
            //         else{
            //             $data['is_released'] = 1;
            //             $data['released_at'] = date('Y-m-d H:i:s');
            //         }
            //     }
            //     else{
            //         $data['is_backload'] = 1;
            //         $data['backload_at'] = date('Y-m-d H:i:s');
            //         $data['backload_reasons_id'] = $value[0];
            //     }
                
            //     TripTicket::where('stores_id',$store_id)
            //     ->where('trip_number',$request->trip_number)
            //     ->where('ref_number',(string)$key)
            //     ->update($data);
            // }
            
            // if($request->has($trips)){
            //     foreach($request->$trips as $key =>  $value){
            //     $data = array();
            //     if($request->trip_type != 'IN'){
            //         $data['is_received'] = 1;
            //         $data['received_at'] = date('Y-m-d H:i:s');
            //         $data['is_backload'] = 0;
            //         $data['backload_at'] = NULL;
            //         $data['backload_reasons_id'] = NULL;
            //     }
            //     else{
            //         $data['is_released'] = 1;
            //         $data['released_at'] = date('Y-m-d H:i:s');
            //         $data['is_backload'] = 0;
            //         $data['backload_at'] = NULL;
            //         $data['backload_reasons_id'] = NULL;
            //     }
            //     TripTicket::where('stores_id',$store_id)
            //     ->where('trip_number',$request->trip_number)
            //     ->where('ref_number',(string)$key)
            //     ->update($data);
            // }
            // }
            foreach($request->$packagings as $key =>  $value){
                $data = array();
                $data['plastic_qty'] = $value["plastic"];
                $data['box_qty'] = $value["box"];
                
                TripTicket::where('stores_id',$store_id)
                ->where('trip_number',$request->trip_number)
                ->where('ref_number',(string)$key)
                ->update($data);
            }
    	    
	    }
	    
	    CRUDBooster::redirect(CRUDBooster::mainpath(),'Success! Trip ticket# '.$request->trip_number.' has been updated!','success')->send();
	}
	
	public function saveOutboundTripTicket(Request $request) {
		$trip_status = TripTicketStatus::where('trip_type','OUT')
		->where('trip_status','Pending')
		->where('status','ACTIVE')->first();
		$trip_counter = DB::table('code_counter')->where('id',1)->first();
		$uniqid = 'TRPTCKT'.str_pad($trip_counter->trip_refcode, 5, "0", STR_PAD_LEFT);
		$data = array();
		foreach ($request->stores as $key => $value) {
			$trip = 'trip'.$value;
			$qty = 'qty'.$value;
			$type = 'trip_type'.$value;
			$trips = array_keys($request->$trip);
			$tripQtyKey = array_keys($request->$qty);
			$tripQtyVal = self::flatten($request->$qty);
			$drCombine = array_combine($tripQtyKey,$tripQtyVal);
			
			foreach ($trips as $key_trip => $value_trip) {
				
				$plastic = $value_trip.'plastic'.$value;
				$box = $value_trip.'box'.$value;
				
				$data[$key.$key_trip]['stores_id'] = $value;
				$data[$key.$key_trip]['trip_number'] = $uniqid;
				$data[$key.$key_trip]['trip_type'] = 'OUT';
				$data[$key.$key_trip]['ref_type'] = $request->$type[$key_trip];
				$data[$key.$key_trip]['ref_number'] = $value_trip;
				$data[$key.$key_trip]['plastic_qty'] = $request->$plastic[0];
				$data[$key.$key_trip]['box_qty'] = $request->$box[0];
				$data[$key.$key_trip]['trip_qty'] = $drCombine[$value_trip];
                $data[$key.$key_trip]['trip_ticket_statuses_id'] = $trip_status->id;
				$data[$key.$key_trip]['created_by'] = CRUDBooster::myId();
				$data[$key.$key_trip]['created_at'] = date('Y-m-d H:i:s');
				$data[$key.$key_trip]['created_date'] = date('Y-m-d');
			}
		}
		
		TripTicket::insert($data);

		DB::table('code_counter')->where('id',1)->increment('trip_refcode');
		CRUDBooster::redirect(CRUDBooster::mainpath('print-trip/').$uniqid,'','')->send();
// 		CRUDBooster::redirect(CRUDBooster::mainpath(),'Success! Trip ticket# '.$uniqid.' created!','success')->send();
		
	}
	
	public function saveReceiveTripTicket(Request $request){
	    $hasBackload = array();
	    
	    $trip_frstatus = TripTicketStatus::where('trip_type','OUT')
	    ->where('trip_status','Fully Received')
	    ->first();
	    
	    $trip_prstatus = TripTicketStatus::where('trip_type','OUT')
	    ->where('trip_status','Partially Received')
	    ->first();
	    
	    foreach($request->ref as $key_trip => $value_trip){
	        $data = array();
	        if(sizeof((array)$request->backload[$value_trip]) > 0){
	            $data = [
	                'is_backload' => 1,
	                'backload_at' => date('Y-m-d H:i:s'),
	                'backload_reasons_id' => $request->backload_reasons[$value_trip],
	                'updated_by' => CRUDBooster::myId(),
	                'logistics_personnel' => $request->logistics_personnel,
	                'store_personnel' => $request->store_personnel,
	            ];
	            
	            array_push($hasBackload,$request->trip_number[$key_trip]);
	        }
	        else{
	            $data = [
	                'is_received' => 1,
	                'received_at' => date('Y-m-d H:i:s'),
	                'updated_by' => CRUDBooster::myId(),
	                'logistics_personnel' => $request->logistics_personnel,
	                'store_personnel' => $request->store_personnel,
	                'trip_ticket_statuses_id' => $trip_frstatus->id,
	            ];
	        }
	        
	        TripTicket::where('stores_id',$request->from_route)
	        ->where('trip_number',$request->trip_number[$key_trip])
	        ->where('ref_number',$value_trip)
	        ->update($data);
	        
	    }
	    
	    if(!empty($hasBackload)){
	        foreach($hasBackload as $backload_trip){
	            TripTicket::where('stores_id',$request->from_route)
    	        ->where('trip_number',$backload_trip)
    	        ->update(['trip_ticket_statuses_id'=>$trip_prstatus->id]); 
	        } 
	    }
        CRUDBooster::insertLog(trans("crudbooster.trip_ticket_received", ['ref_number' =>$request->trip_number[0]]));
		CRUDBooster::redirect(CRUDBooster::mainpath(),'Success! Trip ticket has been received!','success')->send();
	}
	
	public function saveReleaseTripTicket(Request $request){
	    $hasBackload = array();
	    
	    $trip_frstatus = TripTicketStatus::where('trip_type','IN')
	    ->where('trip_status','Fully Released')
	    ->first();
	    
	    $trip_prstatus = TripTicketStatus::where('trip_type','IN')
	    ->where('trip_status','Partially Released')
	    ->first();
	    
	    foreach($request->ref as $key_trip => $value_trip){
	        $data = array();
	        $plastic = $value_trip.'plastic';
	        $box = $value_trip.'box';
	        
	        if(sizeof((array)$request->backload[$value_trip]) > 0){
	            $data = [
	                'is_backload' => 1,
	                'backload_at' => date('Y-m-d H:i:s'),
	                'backload_reasons_id' => $request->backload_reasons[$value_trip],
	                'updated_by' => CRUDBooster::myId(),
	                'logistics_personnel' => $request->logistics_personnel,
	                'store_personnel' => $request->store_personnel,
	            ];
	            
	            array_push($hasBackload,$request->trip_number[$key_trip]);
	        }
	        else{
	            $data = [
	                'is_released' => 1,
	                'plastic_qty' => $request->$plastic[0],
	                'box_qty' => $request->$box[0],
	                'released_at' => date('Y-m-d H:i:s'),
	                'updated_by' => CRUDBooster::myId(),
	                'logistics_personnel' => $request->logistics_personnel,
	                'store_personnel' => $request->store_personnel,
	                'trip_ticket_statuses_id' => $trip_frstatus->id,
	            ];
	        }
	        
	        TripTicket::where('stores_id',$request->from_route)
	        ->where('trip_number',$request->trip_number[$key_trip])
	        ->where('ref_number',$value_trip)
	        ->update($data);
	        
	    }
	    
	    if(!empty($hasBackload)){
	        foreach($hasBackload as $backload_trip){
	            TripTicket::where('stores_id',$request->from_route)
    	        ->where('trip_number',$backload_trip)
    	        ->update(['trip_ticket_statuses_id'=>$trip_prstatus->id]); 
	        } 
	    }
        CRUDBooster::insertLog(trans("crudbooster.trip_ticket_released", ['ref_number' =>$request->trip_number[0]]));
		CRUDBooster::redirect(CRUDBooster::mainpath(),'Success! Trip ticket has been released!','success')->send();
	}

	public function saveInboundTripTicket(Request $request) {
		$trip_status = TripTicketStatus::where('trip_type','IN')
		->where('trip_status','Pending')
		->where('status','ACTIVE')->first();
		$trip_counter = DB::table('code_counter')->where('id',1)->first();
		$uniqid = 'TRPTCKT'.str_pad($trip_counter->trip_refcode, 5, "0", STR_PAD_LEFT);
		$data = array();
		foreach ($request->stores as $key => $value) {
			$trip = 'trip'.$value;
			$qty = 'qty'.$value;
			$type = 'trip_type'.$value;
			$trips = array_keys($request->$trip);
			$tripQtyKey = array_keys($request->$qty);
			$tripQtyVal = self::flatten($request->$qty);
			$drCombine = array_combine($tripQtyKey,$tripQtyVal);
			
			foreach ($trips as $key_trip => $value_trip) {
				
				$data[$key.$key_trip]['stores_id'] = $value;
				$data[$key.$key_trip]['trip_number'] = $uniqid;
				$data[$key.$key_trip]['trip_type'] = 'IN';
				$data[$key.$key_trip]['ref_type'] = $request->$type[$key_trip];
				$data[$key.$key_trip]['ref_number'] = $value_trip;
				$data[$key.$key_trip]['trip_qty'] = $drCombine[$value_trip];
                $data[$key.$key_trip]['trip_ticket_statuses_id'] = $trip_status->id;
				$data[$key.$key_trip]['created_by'] = CRUDBooster::myId();
				$data[$key.$key_trip]['created_at'] = date('Y-m-d H:i:s');
				$data[$key.$key_trip]['created_date'] = date('Y-m-d');
			}
		}
		
		TripTicket::insert($data);

		DB::table('code_counter')->where('id',1)->increment('trip_refcode');
		CRUDBooster::redirect(CRUDBooster::mainpath('print-trip/').$uniqid,'','')->send();
// 		CRUDBooster::redirect(CRUDBooster::mainpath(),'Success! Trip ticket# '.$uniqid.' created!','success')->send();
		
	}

	public function flatten(array $array) {
		$return = array();
		array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
		return $return;
	}
    
    public function exportTripTicket(Request $request)
	{
		ini_set('max_execution_time',10000);

		$trip_tickets = TripTicket::select('trip_tickets.*','backload_reasons.backload_reason','trip_ticket_statuses.trip_status','stores.pos_warehouse_name as route_name')
			->join('stores','trip_tickets.stores_id','=','stores.id')
			->join('trip_ticket_statuses','trip_tickets.trip_ticket_statuses_id','=','trip_ticket_statuses.id')
			->leftJoin('backload_reasons','trip_tickets.backload_reasons_id','=','backload_reasons.id');
			
		if(\Request::get('filter_column')) {

			$filter_column = \Request::get('filter_column');

			$trip_tickets->where(function($w) use ($filter_column,$fc) {
				foreach($filter_column as $key=>$fc) {

					$value = @$fc['value'];
					$type  = @$fc['type'];

					if($type == 'empty') {
						$w->whereNull($key)->orWhere($key,'');
						continue;
					}

					if($value=='' || $type=='') continue;

					if($type == 'between') continue;

					switch($type) {
						default:
							if($key && $type && $value) $w->where($key,$type,$value);
						break;
						case 'like':
						case 'not like':
							$value = '%'.$value.'%';
							if($key && $type && $value) $w->where($key,$type,$value);
						break;
						case 'in':
						case 'not in':
							if($value) {
								$value = explode(',',$value);
								if($key && $value) $w->whereIn($key,$value);
							}
						break;
					}
				}
			});

			foreach($filter_column as $key=>$fc) {
				$value = @$fc['value'];
				$type  = @$fc['type'];
				$sorting = @$fc['sorting'];

				if($sorting!='') {
					if($key) {
						$trip_tickets->orderby($key,$sorting);
						$filter_is_orderby = true;
					}
				}

				if ($type=='between') {
					if($key && $value) $trip_tickets->whereBetween($key,$value);
				}

				else {
					continue;
				}
			}
		}
		if (!CRUDBooster::isSuperadmin() && !in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
			$trip_tickets->whereIn('stores_id',CRUDBooster::myStore());
		}
		$trip_tickets->orderBy('trip_tickets.trip_number', 'asc');
		$tripItems = $trip_tickets->get();
		
		$headings = array(
			'TRIP NUMBER',
			'REF #',
			'REF TYPE',
			'TRIP TYPE',
			'STORE',
			'QTY',
			'PLASTIC QTY',
			'BOX QTY',
			'RELEASED DATE',
			'RECEIVED DATE',
			'BACKLOAD REASON',
			'LOGISTICS PERSONNEL',
			'STORE PERSONNEL',
			'STATUS',
			'CREATED DATE');

		Excel::create('Export Trip Ticket - '.date("Ymd H:i:sa"), function($excel) use ($tripItems,$headings) {
			$excel->sheet('trips', function($sheet) use ($tripItems,$headings) {
				// Set auto size for sheet
				$sheet->setAutoSIZE(true);
				$sheet->setColumnFormat(array(
				// 	'D' => '@',
				));

				foreach($tripItems as $item) {

					$items_array[] = array(
						$item->trip_number,
						$item->ref_number,
						$item->ref_type,
						$item->trip_type,	
						$item->route_name,
						$item->trip_qty,
						$item->plastic_qty,
						$item->box_qty,
						$item->released_at,
						$item->received_at,
						$item->backload_reason,
						$item->logistics_personnel,
						$item->store_personnel,
						$item->trip_status,
						$item->created_at
					);
				}
				
				$sheet->fromArray($items_array, null, 'A1', false, false);
				$sheet->prependRow(1, $headings);
				$sheet->row(1, function($row) {
					$row->setBackground('#FFFF00');
					$row->setAlignment('center');
				});
				
			});
		})->export('xlsx');
	}

}
<?php namespace App\Http\Controllers;

	use Session;
	use DB;
	use CRUDBooster;
	use Illuminate\Http\Request;
	use App\ApprovalMatrix;
	use Excel;

	class AdminPulloutHistoryController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->col[] = ["label"=>"Transport By","name"=>"transport_types_id","join"=>"transport_types,transport_type"];
			if (in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
				    $this->col[] = ["label"=>"Scheduled Date","name"=>"schedule_date"];
			}
			$this->col[] = ["label"=>"Received Date","name"=>"received_st_date"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_date"];
			
			$this->form = [];
			
			$this->addaction = array();
			if(in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])){
				$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('print').'/[st_document_number]','icon'=>'fa fa-print','color'=>'info','showIf'=>"[status]=='FOR RECEIVING'"];
			}
			if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(), ["Requestor", "Requestor II","Online Requestor","Online Requestor II"])){
				$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('print').'/[st_document_number]','icon'=>'fa fa-print','color'=>'info','showIf'=>"[sor_number]!=''"];
			}
			if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(), ["Online WSDM"])){
				$this->addaction[] = ['title'=>'Print Pick List','url'=>CRUDBooster::mainpath('print-picklist').'/[st_document_number]','icon'=>'fa fa-print','color'=>'info','showIf'=>"[status]!='CLOSED'"];
			}
			$this->addaction[] = ['title'=>'Detail','url'=>CRUDBooster::mainpath('details').'/[st_document_number]?return_url='.urlencode(\Request::fullUrl()),'icon'=>'fa fa-eye','color'=>'primary'];
	        
			
	        $this->button_selected = array();
            if(CRUDBooster::myPrivilegeName() == "Online WSDM"){
				$this->button_selected[] = ['label'=>'Print Picklist', 'icon'=>'fa fa-print', 'name'=>'print_picklist'];
			}
			else if(CRUDBooster::myPrivilegeName() == "Online Ops"){
				$this->button_selected[] = ['label'=>'Close Trxn', 'icon'=>'fa fa-print', 'name'=>'close_transaction'];
			}
			
			$this->index_button = array();
			if(CRUDBooster::getCurrentMethod() == 'getIndex') {
				if(CRUDBooster::myPrivilegeName() == "Warehouse"){
					$this->index_button[] = ["title"=>"Export STW with Serial","label"=>"Export STW with Serial",'color'=>'info',"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export-stw-serialized').'?'.urldecode(http_build_query(@$_GET))];
					$this->index_button[] = ["title"=>"Export STW","label"=>"Export STW",'color'=>'primary',"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export-stw').'?'.urldecode(http_build_query(@$_GET))];
				}
				if(CRUDBooster::myPrivilegeName() == "RMA"){
					$this->index_button[] = ["title"=>"Export STR with Serial","label"=>"Export STR with Serial",'color'=>'info',"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export-str-serialized').'?'.urldecode(http_build_query(@$_GET))];
					$this->index_button[] = ["title"=>"Export STR","label"=>"Export STR",'color'=>'primary',"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export-str').'?'.urldecode(http_build_query(@$_GET))];
				}
				if(CRUDBooster::isSuperadmin() || !in_array(CRUDBooster::myPrivilegeName(),["RMA", "Warehouse"])){
					$this->index_button[] = ["title"=>"Export STW/STR with Serial","label"=>"Export STW/STR with Serial",'color'=>'info',"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export-stwr-serialized').'?'.urldecode(http_build_query(@$_GET))];
					$this->index_button[] = ["title"=>"Export STW/STR","label"=>"Export STW/STR",'color'=>'primary',"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export-stwr').'?'.urldecode(http_build_query(@$_GET))];
				}
			}
	        
	    }
		
	    public function hook_query_index(&$query) {
	        //Your code here
			$query->select('pullout.st_document_number',
				'pullout.wh_from',
				'pullout.wh_to',
				'pullout.status',
				'pullout.created_date')->distinct();

	        if(!CRUDBooster::isSuperadmin() && !in_array(CRUDBooster::myPrivilegeName(), ["Audit","Inventory Control","Merch"])){
				$store = DB::table('stores')->where('id', CRUDBooster::myStore())->first();
				if (in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
					$query->where('transport_types_id',1);
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
	
					$query->whereIn('pullout.stores_id', array_values((array)$storeList));
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Warehouse","Warehouse Online","RMA"])){
					$query->where('wh_to',$store->pos_warehouse);
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Online Ops","Retail Ops","Franchise Ops","Online Viewer"])) {
				    if(empty($store)){
    					$query->where('pullout.channel_id',CRUDBooster::myChannel());
				    }
				    else{
				        $query->where('pullout.channel_id',CRUDBooster::myChannel())
    					->whereIn('pullout.stores_id',CRUDBooster::myStore());
				    }
				}
				elseif(CRUDBooster::myPrivilegeName() == "Rtl Fra Ops"){
					$query->whereIn('pullout.channel_id',[1,2]);
				}
				elseif(CRUDBooster::myPrivilegeName() == "Franchise Viewer"){
					$query->whereIn('pullout.stores_id',CRUDBooster::myStore());
				}
				elseif(CRUDBooster::myPrivilegeName() == "Reports"){
					$query->whereIn('pullout.channel_id',[1,2,4,6,7,10,11]);
				}
				elseif(CRUDBooster::myPrivilegeName() == "Rtl Onl Viewer"){
					$query->where(function($subquery) {
						$subquery->where('pullout.channel_id',4)
						->orWhereIn('pullout.reason_id',['203','202','R-28','R-27']);
					});
				}
				elseif(CRUDBooster::myPrivilegeName() == "Distri Ops"){
					$query->where(function($subquery) {
                        $subquery->whereIn('pullout.channel_id',[6,7,10,11])
                        ->orWhereIn('pullout.reason_id',['173','R-12']);
                    });
				}
				else{
					$query->whereIn('pullout.stores_id',CRUDBooster::myStore());
				}
			}
	    }
		   
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
			if($column_index == 7){
				if($column_value == "Logistics"){
					$column_value = '<span class="label label-default">LOGISTICS</span>';
				}
				elseif($column_value == "Hand Carry"){
					$column_value = '<span class="label label-primary">HAND CARRY</span>';
				}
			}

	    }
		
		public function printPicklist($st_number) {
			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'Pullout Details';

			$data['stDetails'] = DB::table('pullout')->where('st_document_number', $st_number)->get();
			$data['stReason'] = DB::table('reason')->where('bea_mo_reason', $data['stDetails'][0]->reason_id)->first();
			if(count((array)$data['stReason']) == 0){
				$data['stReason'] = DB::table('reason')->where('bea_so_reason', $data['stDetails'][0]->reason_id)->first();
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
			$this->cbView("pullout.print-picklist", $data);
			// $datatopdf = \PDF::loadView('pullout.print', $data);
			
			// return $datatopdf->download('stw-picklist-'.date('YmdHis').'.pdf');
		}

		public function getDetail($st_number) {

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
				->select('pullout.*','reason.pullout_reason','reason.id as reason_id','transport_types.transport_type', 'rejectedby.name as rejectedby','approvedby.name as approvedby')
				->get();

			if(count($data['stDetails']) == 0){
				$data['stDetails'] = DB::table('pullout')
					->join('reason', 'pullout.reason_id', '=', 'reason.bea_so_reason')
					->leftJoin('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
					->leftJoin('cms_users as approvedby', 'pullout.approved_by', '=', 'approvedby.id')
				    ->leftJoin('cms_users as rejectedby', 'pullout.rejected_by', '=', 'rejectedby.id')
					->where('st_document_number', $st_number)
					->select('pullout.*','reason.pullout_reason','reason.id as reason_id','transport_types.transport_type', 'rejectedby.name as rejectedby','approvedby.name as approvedby')
					->get();
			}

			$data['transfer_from'] = DB::table('stores')->where('id',$data['stDetails'][0]->stores_id)->first();

			$data['transfer_to'] = DB::table('stores')->where('pos_warehouse',$data['stDetails'][0]->wh_to)->first();

			$items = DB::table('pullout')
			->where('st_document_number',$st_number)
			->select('id','item_code','quantity','problems','problem_detail')
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
					'item_description' => $item_detail->item_description,
					'price' => $item_detail->store_cost,
					'st_quantity' => $value->quantity,
					'problems' => $value->problems.' : '.$value->problem_detail,
					'st_serial_numbers' => $serial_data
				];
			}

			$data['items'] = $item_data;
			
			$this->cbView("pullout.detail", $data);
		}

		public function getPrint($st_number) {

			$this->cbLoader();

			$data = array();
			
			$data['page_title'] = 'Pullout Details';

			$data['stDetails'] = DB::table('pullout')->where('st_document_number', $st_number)
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

			$this->cbView("pullout.print", $data);
		}
		public function exportSTW(Request $request)
		{
			$store = DB::table('stores')->where('id', CRUDBooster::myStore())->first();
			ini_set('memory_limit', '-1');
			Excel::create('Export STW - '.date("Ymd H:i:sa"), function($excel) use ($store){
				$excel->sheet('stw', function($sheet) use ($store){
					// Set auto size for sheet
					$sheet->setAutoSIZE(true);
					$sheet->setColumnFormat(array(
						'E' => '@',
					));

					$stw_item = DB::table('pullout')->select(
						'pullout.st_document_number as st_number',
						'pullout.received_st_number',
						'pullout.sor_number',
						'reason.pullout_reason',
						'so_reason.pullout_reason as so_pullout_reason',
						'pullout.item_code as digits_code',
						'items.upc_code',
						'items.item_description',
						'stores.bea_so_store_name as source',
						'pullout.wh_to as destination',
						'pullout.quantity as stw_quantity',
						'transport_types.transport_type as transport_by',
						'pullout.hand_carrier',
						'pullout.transaction_type',
						'pullout.created_at as created_date',
						'pullout.received_st_date as received_date',
						'pullout.status')
					->leftJoin('items', 'pullout.item_code', '=', 'items.digits_code')
					->leftJoin('reason', 'pullout.reason_id', '=', 'reason.bea_mo_reason')
					->leftJoin('reason as so_reason', 'pullout.reason_id', '=', 'so_reason.bea_so_reason')
					->leftJoin('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
					->leftJoin('stores', 'pullout.stores_id', '=', 'stores.id');
					
					if(\Request::get('filter_column')) {

						$filter_column = \Request::get('filter_column');
	
						$stw_item->where(function($w) use ($filter_column,$fc) {
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
									$stw_item->orderby($key,$sorting);
									$filter_is_orderby = true;
								}
							}
	
							if ($type=='between') {
								if($key && $value) $stw_item->whereBetween($key,$value);
							}
	
							else {
								continue;
							}
						}
					}
					if(!CRUDBooster::isSuperadmin()){
						if (in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
							$stw_item->where('pullout.transport_types_id',1);
						}
						elseif(in_array(CRUDBooster::myPrivilegeName(), ["Warehouse","RMA"])){
							$stw_item->where('pullout.wh_to',$store->pos_warehouse)->distinct();
						}
						elseif(CRUDBooster::myPrivilegeName() == "Rtl Fra Ops"){
						    $stw_item->whereIn('pullout.channel_id', [1,2]);
						}
						elseif(CRUDBooster::myPrivilegeName() == "Franchise Viewer"){
						    $stw_item->whereIn('pullout.stores_id',CRUDBooster::myStore());
						}
						elseif(CRUDBooster::myPrivilegeName() == "Reports"){
						    $stw_item->whereIn('pullout.channel_id', [1,2,4,6,7,10,11]);
						}
						elseif(CRUDBooster::myPrivilegeName() == "Rtl Onl Viewer"){
						    $stw_item->where(function($subquery) {
                                $subquery->where('pullout.channel_id',4)
                                ->orWhereIn('pullout.reason_id',['203','202','R-28','R-27']);
                            });
						}
						elseif(in_array(CRUDBooster::myPrivilegeName(), ["Online Ops","Retail Ops","Franchise Ops","Online Viewer"])){
						    if(empty($store)){
						        $stw_item->where('pullout.channel_id', CRUDBooster::myChannel());
						    }
						    else{
						        $stw_item->where('pullout.channel_id', CRUDBooster::myChannel())
						        ->whereIn('pullout.stores_id',CRUDBooster::myStore());
						    }
						}
						else{
							$stw_item->where('stores.bea_so_store_name',$store->bea_so_store_name);
						}
					}
					$stw_item->where('pullout.transaction_type','STW')->orderBy('pullout.st_document_number', 'asc');
					$stwItems = $stw_item->get();
					
					$headings = array('ST #',
						'RECEIVED ST #',
						'MOR/SOR #',
						'REASON',
						'DIGITS CODE',
						'UPC CODE',
						'ITEM DESCRIPTION',
						'SOURCE',
						'DESTINATION',
						'QTY',
						'TRANSPORT BY',
						'TRANSACTION TYPE',
						'CREATED DATE',
						'RECEIVED DATE',
						'STATUS');

					foreach($stwItems as $item) {
	
						$items_array[] = array(
							$item->st_number,
							$item->received_st_number,
							$item->sor_number,
							(empty($item->pullout_reason)) ? $item->so_pullout_reason : $item->pullout_reason,
							$item->digits_code,	
							'="'.$item->upc_code.'"',			
							$item->item_description,	
							$item->source,
							$item->destination,
							$item->stw_quantity,
							$item->transport_by.' : '.$item->hand_carrier,
							$item->transaction_type,
							$item->created_date,
							$item->received_date,
							$item->status
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

		public function exportSTWSerialized(Request $request)
		{
			$store = DB::table('stores')->where('id', CRUDBooster::myStore())->first();
			ini_set('memory_limit', '-1');
			Excel::create('Export STW with Serial- '.date("Ymd H:i:sa"), function($excel) use ($store){
				$excel->sheet('stw-serial', function($sheet) use ($store){
					// Set auto size for sheet
					$sheet->setAutoSIZE(true);
					$sheet->setColumnFormat(array(
						'E' => '@',
					));

					$stw_item = DB::table('pullout')->select(
						'pullout.st_document_number as st_number',
						'pullout.received_st_number',
						'pullout.sor_number',
						'reason.pullout_reason',
						'so_reason.pullout_reason as so_pullout_reason',
						'pullout.item_code as digits_code',
						'items.upc_code',
						'items.item_description',
						'stores.bea_so_store_name as source',
						'pullout.wh_to as destination',
						'pullout.quantity as stw_quantity',
						'serials.serial_number',
						'transport_types.transport_type as transport_by',
						'pullout.hand_carrier',
						'pullout.transaction_type',
						'pullout.created_at as created_date',
						'pullout.received_st_date as received_date',
						'pullout.status')
					->leftJoin('items', 'pullout.item_code', '=', 'items.digits_code')
					->leftJoin('reason', 'pullout.reason_id', '=', 'reason.bea_mo_reason')
					->leftJoin('reason as so_reason', 'pullout.reason_id', '=', 'so_reason.bea_so_reason')
					->leftJoin('serials', 'pullout.id', '=', 'serials.pullout_id')
					->leftJoin('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
					->leftJoin('stores', 'pullout.stores_id', '=', 'stores.id');
					
					if(\Request::get('filter_column')) {

						$filter_column = \Request::get('filter_column');
	
						$stw_item->where(function($w) use ($filter_column,$fc) {
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
									$stw_item->orderby($key,$sorting);
									$filter_is_orderby = true;
								}
							}
	
							if ($type=='between') {
								if($key && $value) $stw_item->whereBetween($key,$value);
							}
	
							else {
								continue;
							}
						}
					}
					if(!CRUDBooster::isSuperadmin()){
						if (in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
							$stw_item->where('pullout.transport_types_id',1);
						}
						elseif(in_array(CRUDBooster::myPrivilegeName(), ["Warehouse","RMA"])){
							$stw_item->where('pullout.wh_to',$store->pos_warehouse)->distinct();
						}
						elseif(CRUDBooster::myPrivilegeName() == "Rtl Fra Ops"){
						    $stw_item->whereIn('pullout.channel_id', [1,2]);
						}
						elseif(CRUDBooster::myPrivilegeName() == "Franchise Viewer"){
						    $stw_item->whereIn('pullout.stores_id',CRUDBooster::myStore());
						}
						elseif(CRUDBooster::myPrivilegeName() == "Reports"){
						    $stw_item->whereIn('pullout.channel_id', [1,2,4,6,7,10,11]);
						}
						elseif(CRUDBooster::myPrivilegeName() == "Rtl Onl Viewer"){
						    $stw_item->where(function($subquery) {
                                $subquery->where('pullout.channel_id',4)
                                ->orWhereIn('pullout.reason_id',['203','202','R-28','R-27']);
                            });
						}
						elseif(in_array(CRUDBooster::myPrivilegeName(), ["Online Ops","Retail Ops","Franchise Ops","Online Viewer"])){
						    if(empty($store)){
						        $stw_item->where('pullout.channel_id', CRUDBooster::myChannel());
						    }
						    else{
						        $stw_item->where('pullout.channel_id', CRUDBooster::myChannel())->whereIn('pullout.stores_id',CRUDBooster::myStore());
						    }
						}
						else{
							$stw_item->where('stores.bea_so_store_name',$store->bea_so_store_name);
						}
					}
					$stw_item->where('pullout.transaction_type','STW')->orderBy('pullout.st_document_number', 'asc');
					$stwItems = $stw_item->get();
					
					$headings = array('ST #',
						'RECEIVED ST #',
						'MOR/SOR #',
						'REASON',
						'DIGITS CODE',
						'UPC CODE',
						'ITEM DESCRIPTION',
						'SOURCE',
						'DESTINATION',
						'QTY',
						'SERIAL #',
						'TRANSPORT BY',
						'TRANSACTION TYPE',
						'CREATED DATE',
						'RECEIVED DATE',
						'STATUS');

					foreach($stwItems as $item) {
	
						$items_array[] = array(
							$item->st_number,
							$item->received_st_number,
							$item->sor_number,
							(empty($item->pullout_reason)) ? $item->so_pullout_reason : $item->pullout_reason,
							$item->digits_code,	
							'="'.$item->upc_code.'"',			
							$item->item_description,	
							$item->source,
							$item->destination,
							(empty($item->serial_number)) ? $item->stw_quantity : 1,
							$item->serial_number,
							$item->transport_by.' : '.$item->hand_carrier,
							$item->transaction_type,
							$item->created_date,
							$item->received_date,
							$item->status
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

		public function exportSTR(Request $request)
		{
			$store = DB::table('stores')->where('id', CRUDBooster::myStore())->first();
			ini_set('memory_limit', '-1');
			Excel::create('Export STR - '.date("Ymd H:i:sa"), function($excel) use ($store){
				$excel->sheet('str', function($sheet) use ($store){
					// Set auto size for sheet
					$sheet->setAutoSIZE(true);
					$sheet->setColumnFormat(array(
						'E' => '@',
					));

					$stw_item = DB::table('pullout')->select(
						'pullout.st_document_number as st_number',
						'pullout.received_st_number',
						'pullout.sor_number',
						'reason.pullout_reason',
						'so_reason.pullout_reason as so_pullout_reason',
						'pullout.item_code as digits_code',
						'items.upc_code',
						'items.brand',
						'items.item_description',
						'stores.bea_so_store_name as source',
						'pullout.wh_to as destination',
						'pullout.quantity as stw_quantity',
						'transport_types.transport_type as transport_by',
						'pullout.hand_carrier',
						'pullout.transaction_type',
						'pullout.problems',
						'pullout.problem_detail',
						'pullout.memo',
						'pullout.created_at as created_date',
						'pullout.received_st_date as received_date',
						'pullout.status')
					->leftJoin('items', 'pullout.item_code', '=', 'items.digits_code')
					->leftJoin('reason', 'pullout.reason_id', '=', 'reason.bea_mo_reason')
					->leftJoin('reason as so_reason', 'pullout.reason_id', '=', 'so_reason.bea_so_reason')
					->leftJoin('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
					->leftJoin('stores', 'pullout.stores_id', '=', 'stores.id');
					
					if(\Request::get('filter_column')) {

						$filter_column = \Request::get('filter_column');
	
						$stw_item->where(function($w) use ($filter_column,$fc) {
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
									$stw_item->orderby($key,$sorting);
									$filter_is_orderby = true;
								}
							}
	
							if ($type=='between') {
								if($key && $value) $stw_item->whereBetween($key,$value);
							}
	
							else {
								continue;
							}
						}
					}
					if(!CRUDBooster::isSuperadmin()){
						if (in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
							$stw_item->where('pullout.transport_types_id',1);
						}
						elseif(in_array(CRUDBooster::myPrivilegeName(), ["Warehouse","RMA"])){
							$stw_item->where('pullout.wh_to',$store->pos_warehouse)->distinct();
						}
						elseif(CRUDBooster::myPrivilegeName() == "Rtl Fra Ops"){
						    $stw_item->whereIn('pullout.channel_id', [1,2]);
						}
						elseif(CRUDBooster::myPrivilegeName() == "Franchise Viewer"){
						    $stw_item->whereIn('pullout.stores_id',CRUDBooster::myStore());
						}
						elseif(CRUDBooster::myPrivilegeName() == "Reports"){
						    $stw_item->whereIn('pullout.channel_id', [1,2,4,6,7,10,11]);
						}
						elseif(CRUDBooster::myPrivilegeName() == "Rtl Onl Viewer"){
						    $stw_item->where(function($subquery) {
                                $subquery->where('pullout.channel_id',4)
                                ->orWhereIn('pullout.reason_id',['203','202','R-28','R-27']);
                            });
						}
						elseif(in_array(CRUDBooster::myPrivilegeName(), ["Online Ops","Retail Ops","Franchise Ops","Online Viewer"])){
						    $stw_item->where('pullout.channel_id', CRUDBooster::myChannel());
						}
						else{
							$stw_item->where('stores.bea_so_store_name',$store->bea_so_store_name);
						}
					}
					$stw_item->where('pullout.transaction_type','RMA')->orderBy('pullout.st_document_number', 'asc');
					$stwItems = $stw_item->get();
					
					$headings = array('ST #',
						'RECEIVED ST #',
						'MOR/SOR #',
						'REASON',
						'DIGITS CODE',
						'UPC CODE',
						'BRAND',
						'ITEM DESCRIPTION',
						'SOURCE',
						'DESTINATION',
						'QTY',
						'TRANSPORT BY',
						'TRANSACTION TYPE',
						'PROBLEM DETAILS',
						'CREATED DATE',
						'RECEIVED DATE',
						'NOTES',
						'STATUS');

					foreach($stwItems as $item) {
	
						$items_array[] = array(
							$item->st_number,
							$item->received_st_number,
							$item->sor_number,
							(empty($item->pullout_reason)) ? $item->so_pullout_reason : $item->pullout_reason,
							$item->digits_code,	
							'="'.$item->upc_code.'"',	
							$item->brand,
							$item->item_description,	
							$item->source,
							$item->destination,
							$item->stw_quantity,
							$item->transport_by.' : '.$item->hand_carrier,
							$item->transaction_type,
							$item->problems.' : '.$item->problem_detail,
							$item->created_date,
							$item->received_date,
							$item->memo,
							$item->status
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

		public function exportSTRSerialized(Request $request)
		{
			$store = DB::table('stores')->where('id', CRUDBooster::myStore())->first();
			ini_set('memory_limit', '-1');
			Excel::create('Export STR with Serial- '.date("Ymd H:i:sa"), function($excel) use ($store){
				$excel->sheet('str-serial', function($sheet) use ($store){
					// Set auto size for sheet
					$sheet->setAutoSIZE(true);
					$sheet->setColumnFormat(array(
						'E' => '@',
					));

					$stw_item = DB::table('pullout')->select(
						'pullout.st_document_number as st_number',
						'pullout.received_st_number',
						'pullout.sor_number',
						'reason.pullout_reason',
						'so_reason.pullout_reason as so_pullout_reason',
						'pullout.item_code as digits_code',
						'items.upc_code',
						'items.upc_code',
						'items.item_description',
						'stores.bea_so_store_name as source',
						'pullout.wh_to as destination',
						'pullout.quantity as stw_quantity',
						'serials.serial_number',
						'transport_types.transport_type as transport_by',
						'pullout.hand_carrier',
						'pullout.transaction_type',
						'pullout.problems',
						'pullout.problem_detail',
						'pullout.memo',
						'pullout.created_at as created_date',
						'pullout.received_st_date as received_date',
						'pullout.status')
					->leftJoin('items', 'pullout.item_code', '=', 'items.digits_code')
					->leftJoin('reason', 'pullout.reason_id', '=', 'reason.bea_mo_reason')
					->leftJoin('reason as so_reason', 'pullout.reason_id', '=', 'so_reason.bea_so_reason')
					->leftJoin('serials', 'pullout.id', '=', 'serials.pullout_id')
					->leftJoin('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
					->leftJoin('stores', 'pullout.stores_id', '=', 'stores.id');
					
					if(\Request::get('filter_column')) {

						$filter_column = \Request::get('filter_column');
	
						$stw_item->where(function($w) use ($filter_column,$fc) {
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
									$stw_item->orderby($key,$sorting);
									$filter_is_orderby = true;
								}
							}
	
							if ($type=='between') {
								if($key && $value) $stw_item->whereBetween($key,$value);
							}
	
							else {
								continue;
							}
						}
					}
					if(!CRUDBooster::isSuperadmin()){
						if (in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
							$stw_item->where('pullout.transport_types_id',1);
						}
						elseif(in_array(CRUDBooster::myPrivilegeName(), ["Warehouse","RMA"])){
							$stw_item->where('pullout.wh_to',$store->pos_warehouse)->distinct();
						}
						elseif(CRUDBooster::myPrivilegeName() == "Rtl Fra Ops"){
						    $stw_item->whereIn('pullout.channel_id', [1,2]);
						}
						elseif(CRUDBooster::myPrivilegeName() == "Franchise Viewer"){
						    $stw_item->whereIn('pullout.stores_id',CRUDBooster::myStore());
						}
						elseif(CRUDBooster::myPrivilegeName() == "Reports"){
						    $stw_item->whereIn('pullout.channel_id', [1,2,4,6,7,10,11]);
						}
						elseif(CRUDBooster::myPrivilegeName() == "Rtl Onl Viewer"){
						    $stw_item->where(function($subquery) {
                                $subquery->where('pullout.channel_id',4)
                                ->orWhereIn('pullout.reason_id',['203','202','R-28','R-27']);
                            });
						}
						elseif(in_array(CRUDBooster::myPrivilegeName(), ["Online Ops","Retail Ops","Franchise Ops","Online Viewer"])){
						    $stw_item->where('pullout.channel_id', CRUDBooster::myChannel());
						}
						else{
							$stw_item->where('stores.bea_so_store_name',$store->bea_so_store_name);
						}
					}
					$stw_item->where('pullout.transaction_type','RMA')->orderBy('pullout.st_document_number', 'asc');
					$stwItems = $stw_item->get();
					
					$headings = array('ST #',
						'RECEIVED ST #',
						'MOR/SOR #',
						'REASON',
						'DIGITS CODE',
						'UPC CODE',
						'BRAND',
						'ITEM DESCRIPTION',
						'SOURCE',
						'DESTINATION',
						'QTY',
						'SERIAL #',
						'TRANSPORT BY',
						'TRANSACTION TYPE',
						'PROBLEM DETAILS',
						'CREATED DATE',
						'RECEIVED DATE',
						'NOTES',
						'STATUS');

					foreach($stwItems as $item) {
	
						$items_array[] = array(
							$item->st_number,
							$item->received_st_number,
							$item->sor_number,
							(empty($item->pullout_reason)) ? $item->so_pullout_reason : $item->pullout_reason,
							$item->digits_code,	
							'="'.$item->upc_code.'"',
							$item->brand,
							$item->item_description,	
							$item->source,
							$item->destination,
							(empty($item->serial_number)) ? $item->stw_quantity : 1,
							$item->serial_number,
							$item->transport_by.' : '.$item->hand_carrier,
							$item->transaction_type,
							$item->problems.' : '.$item->problem_detail,
							$item->received_date,
							$item->created_date,
							$item->received_date,
							$item->memo,
							$item->status
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

		public function exportSTWR(Request $request)
		{
			$store = DB::table('stores')->whereIn('id', CRUDBooster::myStore())->first();
			ini_set('memory_limit', '-1');
			ini_set('max_execution_time',10000);
			Excel::create('Export STWR - '.date("Ymd H:i:sa"), function($excel) use ($store){
				$excel->sheet('str', function($sheet) use ($store){
					// Set auto size for sheet
					$sheet->setAutoSIZE(true);
					$sheet->setColumnFormat(array(
						'E' => '@',
					));

					$stw_item = DB::table('pullout')->select(
						'pullout.st_document_number as st_number',
						'pullout.received_st_number',
						'pullout.sor_number',
						'reason.pullout_reason',
						'so_reason.pullout_reason as so_pullout_reason',
						'pullout.item_code as digits_code',
						'items.upc_code',
						'items.brand',
						'items.item_description',
						'stores.bea_so_store_name as source',
						'pullout.wh_to as destination',
						'pullout.quantity as stw_quantity',
						'transport_types.transport_type as transport_by',
						'pullout.hand_carrier',
						'pullout.schedule_date',
						'scheduled_log.name as schedule_by',
						'pullout.transaction_type',
						'pullout.problems',
						'pullout.problem_detail',
						'pullout.memo',
						'pullout.created_at as created_date',
						'pullout.received_st_date as received_date',
						'pullout.status')
					->leftJoin('items', 'pullout.item_code', '=', 'items.digits_code')
					->leftJoin('reason', 'pullout.reason_id', '=', 'reason.bea_mo_reason')
					->leftJoin('reason as so_reason', 'pullout.reason_id', '=', 'so_reason.bea_so_reason')
					->leftJoin('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
					->leftJoin('cms_users as scheduled_log', 'pullout.schedule_by', '=', 'scheduled_log.id')
					->leftJoin('stores', 'pullout.stores_id', '=', 'stores.id');
					
					if(\Request::get('filter_column')) {

						$filter_column = \Request::get('filter_column');
	
						$stw_item->where(function($w) use ($filter_column,$fc) {
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
									$stw_item->orderby($key,$sorting);
									$filter_is_orderby = true;
								}
							}
	
							if ($type=='between') {
								if($key && $value) $stw_item->whereBetween($key,$value);
							}
	
							else {
								continue;
							}
						}
					}
					if(!CRUDBooster::isSuperadmin() && !in_array(CRUDBooster::myPrivilegeName(), ["Audit","Inventory Control","Merch"])){
						if (in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
							$stw_item->where('pullout.transport_types_id',1);
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
			
							$stw_item->whereIn('pullout.stores_id', array_values((array)$storeList));
						}
						elseif(in_array(CRUDBooster::myPrivilegeName(), ["Warehouse","RMA"])){
							$stw_item->where('pullout.wh_to',$store->pos_warehouse)->distinct();
						}
						elseif(CRUDBooster::myPrivilegeName() == "Rtl Fra Ops"){
						    $stw_item->whereIn('pullout.channel_id', [1,2]);
						}
						elseif(CRUDBooster::myPrivilegeName() == "Franchise Viewer"){
						    $stw_item->whereIn('pullout.stores_id',CRUDBooster::myStore());
						}
						elseif(CRUDBooster::myPrivilegeName() == "Reports"){
						    $stw_item->whereIn('pullout.channel_id', [1,2,4,6,7,10,11]);
						}
						elseif(CRUDBooster::myPrivilegeName() == "Rtl Onl Viewer"){
						    $stw_item->where(function($subquery) {
                                $subquery->where('pullout.channel_id',4)
                                ->orWhereIn('pullout.reason_id',['203','202','R-28','R-27']);
                            });
						}
						elseif(CRUDBooster::myPrivilegeName() == "Distri Ops"){
						    $stw_item->where(function($subquery) {
                                $subquery->whereIn('pullout.channel_id',[6,7,10,11])
                                ->orWhereIn('pullout.reason_id',['173','R-12']);
                            });
						}
						elseif(in_array(CRUDBooster::myPrivilegeName(), ["Online Ops","Retail Ops","Franchise Ops","Online Viewer"])){
						    $stw_item->where('pullout.channel_id', CRUDBooster::myChannel());
						}
						elseif(CRUDBooster::myPrivilegeName() == "Online WSDM"){
							$stw_item->whereIn('pullout.stores_id',CRUDBooster::myStore());
						}
						else{
							$stw_item->where('stores.bea_so_store_name',$store->bea_so_store_name);
						}
					}
					$stw_item->orderBy('pullout.st_document_number', 'asc');
					$stwItems = $stw_item->get();
					
					$headings = array('ST/REF #',
						'RECEIVED ST #',
						'MOR/SOR #',
						'REASON',
						'DIGITS CODE',
						'UPC CODE',
						'BRAND',
						'ITEM DESCRIPTION',
						'SOURCE',
						'DESTINATION',
						'QTY',
						'TRANSPORT BY',
						'SCHEDULED DATE/BY',
						'TRANSACTION TYPE',
						'PROBLEM DETAILS',
						'MEMO',
						'CREATED DATE',
						'RECEIVED DATE',
						'STATUS');

					foreach($stwItems as $item) {
	
						$items_array[] = array(
							$item->st_number,
							$item->received_st_number,
							$item->sor_number,
							(empty($item->pullout_reason)) ? $item->so_pullout_reason : $item->pullout_reason,
							$item->digits_code,	
							'="'.$item->upc_code.'"',	
							$item->brand,	
							$item->item_description,	
							$item->source,
							$item->destination,
							$item->stw_quantity,
							(empty($item->hand_carrier)) ? $item->transport_by : $item->transport_by.' : '.$item->hand_carrier,
							(!empty($item->schedule_date)) ? $item->schedule_date.' - '.$item->schedule_by : '',
							$item->transaction_type,
							(!empty($item->problems)) ? $item->problems.' : '.$item->problem_detail : '',
							$item->memo,
							$item->created_date,
							$item->received_date,
							$item->status
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

		public function exportSTWRSerialized(Request $request)
		{
			$store = DB::table('stores')->where('id', CRUDBooster::myStore())->first();
			ini_set('memory_limit', '-1');
			ini_set('max_execution_time',10000);
			Excel::create('Export STWR with Serial- '.date("Ymd H:i:sa"), function($excel) use ($store){
				$excel->sheet('str-serial', function($sheet) use ($store){
					// Set auto size for sheet
					$sheet->setAutoSIZE(true);
					$sheet->setColumnFormat(array(
						'F' => '@',
						'K' => '@',
					));

					$stw_item = DB::table('pullout')->select(
						'pullout.st_document_number as st_number',
						'pullout.received_st_number',
						'pullout.sor_number',
						'reason.pullout_reason',
						'so_reason.pullout_reason as so_pullout_reason',
						'pullout.item_code as digits_code',
						'items.upc_code',
						'items.item_description',
						'stores.bea_so_store_name as source',
						'pullout.wh_to as destination',
						'pullout.quantity as stw_quantity',
						'serials.serial_number',
						'transport_types.transport_type as transport_by',
						'pullout.hand_carrier',
						'pullout.schedule_date',
						'scheduled_log.name as schedule_by',
						'pullout.transaction_type',
						'pullout.problems',
						'pullout.problem_detail',
						'pullout.memo',
						'pullout.created_at as created_date',
						'pullout.received_st_date as received_date',
						'pullout.status')
					->leftJoin('items', 'pullout.item_code', '=', 'items.digits_code')
					->leftJoin('reason', 'pullout.reason_id', '=', 'reason.bea_mo_reason')
					->leftJoin('reason as so_reason', 'pullout.reason_id', '=', 'so_reason.bea_so_reason')
					->leftJoin('serials', 'pullout.id', '=', 'serials.pullout_id')
					->leftJoin('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
					->leftJoin('cms_users as scheduled_log', 'pullout.schedule_by', '=', 'scheduled_log.id')
					->leftJoin('stores', 'pullout.stores_id', '=', 'stores.id');
					
					if(\Request::get('filter_column')) {

						$filter_column = \Request::get('filter_column');
	
						$stw_item->where(function($w) use ($filter_column,$fc) {
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
									$stw_item->orderby($key,$sorting);
									$filter_is_orderby = true;
								}
							}
	
							if ($type=='between') {
								if($key && $value) $stw_item->whereBetween($key,$value);
							}
	
							else {
								continue;
							}
						}
					}
					if(!CRUDBooster::isSuperadmin() && !in_array(CRUDBooster::myPrivilegeName(), ["Audit","Inventory Control","Merch"])){
						if (in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
							$stw_item->where('pullout.transport_types_id',1);
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
			
							$stw_item->whereIn('pullout.stores_id', array_values((array)$storeList));
						}
						elseif(in_array(CRUDBooster::myPrivilegeName(), ["Warehouse","RMA"])){
							$stw_item->where('pullout.wh_to',$store->pos_warehouse)->distinct();
						}
						elseif(CRUDBooster::myPrivilegeName() == "Online WSDM"){
							$stw_item->whereIn('pullout.stores_id',CRUDBooster::myStore());
						}
						elseif(CRUDBooster::myPrivilegeName() == "Rtl Fra Ops"){
						    $stw_item->whereIn('pullout.channel_id', [1,2]);
						}
						elseif(CRUDBooster::myPrivilegeName() == "Franchise Viewer"){
						    $stw_item->whereIn('pullout.stores_id',CRUDBooster::myStore());
						}
						elseif(CRUDBooster::myPrivilegeName() == "Reports"){
						    $stw_item->whereIn('pullout.channel_id', [1,2,4,6,7,10,11]);
						}
						elseif(CRUDBooster::myPrivilegeName() == "Rtl Onl Viewer"){
						    $stw_item->where(function($subquery) {
                                $subquery->where('pullout.channel_id',4)
                                ->orWhereIn('pullout.reason_id',['203','202','R-28','R-27']);
                            });
						}
						elseif(in_array(CRUDBooster::myPrivilegeName(), ["Online Ops","Retail Ops","Franchise Ops","Online Viewer"])){
						    $stw_item->where('pullout.channel_id', CRUDBooster::myChannel());
						}
						else{
							$stw_item->where('stores.bea_so_store_name',$store->bea_so_store_name);
						}
					}
					$stw_item->orderBy('pullout.st_document_number', 'asc');
					$stwItems = $stw_item->get();
					
					$headings = array('ST/REF #',
						'RECEIVED ST #',
						'MOR/SOR #',
						'REASON',
						'DIGITS CODE',
						'UPC CODE',
						'ITEM DESCRIPTION',
						'SOURCE',
						'DESTINATION',
						'QTY',
						'SERIAL #',
						'TRANSPORT BY',
						'SCHEDULED DATE/BY',
						'TRANSACTION TYPE',
						'PROBLEM DETAILS',
						'MEMO',
						'CREATED DATE',
						'RECEIVED DATE',
						'STATUS');

					foreach($stwItems as $item) {
	
						$items_array[] = array(
							$item->st_number,
							$item->received_st_number,
							$item->sor_number,
							(empty($item->pullout_reason)) ? $item->so_pullout_reason : $item->pullout_reason,
							$item->digits_code,	
							'="'.$item->upc_code.'"',			
							$item->item_description,	
							$item->source,
							$item->destination,
							(empty($item->serial_number)) ? $item->stw_quantity : 1,
							$item->serial_number,
							(empty($item->hand_carrier)) ? $item->transport_by : $item->transport_by.' : '.$item->hand_carrier,
							(!empty($item->schedule_date)) ? $item->schedule_date.' - '.$item->schedule_by : '',
							$item->transaction_type,
							(!empty($item->problems)) ? $item->problems.' : '.$item->problem_detail : '',
							$item->memo,
							$item->created_date,
							$item->received_date,
							$item->status
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
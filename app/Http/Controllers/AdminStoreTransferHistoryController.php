<?php namespace App\Http\Controllers;

	use Session;
	use DB;
	use CRUDBooster;
	use Illuminate\Http\Request;
	use App\ApprovalMatrix;
	use Excel;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Reader\Exception;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use App\PosPullHeader;
	use App\PosPullLines;
	use Illuminate\Support\Facades\Log;

	class AdminStoreTransferHistoryController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "st_document_number";
			$this->limit = "20";
			$this->orderby = "created_date,desc,status,asc";
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
			$this->col[] = ["label"=>"Received ST #","name"=>"received_st_number"];
			$this->col[] = ["label"=>"From WH","name"=>"stores_id","join"=>"stores,pos_warehouse_name"];
			$this->col[] = ["label"=>"To WH","name"=>"stores_id_destination","join"=>"stores,pos_warehouse_name"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Transport By","name"=>"transport_types_id","join"=>"transport_types,transport_type"];
			if (in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
				    $this->col[] = ["label"=>"Scheduled Date","name"=>"scheduled_at"];
			}
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_date"];
			
			$this->form = [];
			
			$this->addaction = array();
			if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])){
				$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('print').'/[st_document_number]','icon'=>'fa fa-print','color'=>'info','showIf'=>"[status]=='FOR RECEIVING'"];
			}
			if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(), ["Requestor", "Requestor II", "Gashapon Requestor"])){
				$this->addaction[] = ['title'=>'Print','url'=>CRUDBooster::mainpath('print').'/[st_document_number]','icon'=>'fa fa-print','color'=>'info','showIf'=>"[status]=='PENDING'"];
			}
			if(CRUDBooster::isSuperadmin() || in_array(CRUDBooster::myPrivilegeName(), ["Online WSDM"])){
				$this->addaction[] = ['title'=>'Print Pick List','url'=>CRUDBooster::mainpath('print-picklist').'/[st_document_number]','icon'=>'fa fa-print','color'=>'info'];
			}
			
			$this->addaction[] = ['title'=>'Details','url'=>CRUDBooster::mainpath('details').'/[st_document_number]?return_url='.urlencode(\Request::fullUrl()),'icon'=>'fa fa-eye','color'=>'primary'];
	        
	        $this->button_selected = array();
            if(CRUDBooster::myPrivilegeName() == "Online WSDM"){
				$this->button_selected[] = ['label'=>'Print Picklist', 'icon'=>'fa fa-print', 'name'=>'print_picklist'];
			}
			$this->index_button = array();
			if(CRUDBooster::getCurrentMethod() == 'getIndex'){
				$this->index_button[] = ["title"=>"Export STS with Serial","label"=>"Export STS with Serial",'color'=>'info',"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export-sts-serialized').'?'.urldecode(http_build_query(@$_GET))];
				$this->index_button[] = ["title"=>"Export STS","label"=>"Export STS",'color'=>'primary',"icon"=>"fa fa-download","url"=>CRUDBooster::mainpath('export-sts').'?'.urldecode(http_build_query(@$_GET))];
			}
	        
	    }
		
	    public function hook_query_index(&$query) {
			//Your code here
			$query->select('pos_pull_headers.st_document_number',
						'pos_pull_headers.wh_from',
						'pos_pull_headers.wh_to',
						'pos_pull_headers.status');

			if(!CRUDBooster::isSuperadmin()){
				$store = DB::table('stores')->whereIn('id', CRUDBooster::myStore())->first();
				
				if(in_array(CRUDBooster::myPrivilegeName(), ["Approver","Franchise Approver"])){
					//get approval matrix
					$approvalMatrix = ApprovalMatrix::where('approval_matrix.cms_users_id', CRUDBooster::myId())->get();
				
					$approval_array = array();
					foreach($approvalMatrix as $matrix){
						array_push($approval_array, $matrix->store_list);
					}
					$approval_string = implode(",",$approval_array);
					$storeList = array_map('intval',explode(",",$approval_string));

					$query->whereIn('pos_pull_headers.stores_id', array_values((array)$storeList));

				}
				elseif (in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
					$query->where('pos_pull_headers.transport_types_id',1);
				}
				
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Online Ops","Retail Ops","Franchise Ops","Online Viewer"])) {
				    if(empty($store)){
    					$query->where('pos_pull_headers.channel_id', CRUDBooster::myChannel());  
				    }
				    else{
				        $query->where('pos_pull_headers.channel_id', CRUDBooster::myChannel())
    					->where(function($subquery) use ($store) {
                            $subquery->whereIn('pos_pull_headers.stores_id',CRUDBooster::myStore())->orWhere('pos_pull_headers.wh_to',$store->pos_warehouse);
                        });
				    }
				}
				
				elseif(CRUDBooster::myPrivilegeName() == "Rtl Fra Ops") {
					$query->whereIn('pos_pull_headers.channel_id', [1,2]);   
				}
				
				elseif(CRUDBooster::myPrivilegeName() == "Reports") {
					$query->whereIn('pos_pull_headers.channel_id', [1,2,4]);   
				}
				
				elseif(CRUDBooster::myPrivilegeName() == "Rtl Onl Viewer") {
					
					$query->whereIn('pos_pull_headers.channel_id', [1,4]);   
				}

				elseif(CRUDBooster::myPrivilegeName() == "Franchise Viewer"){
					
					$query->where(function($subquery) use ($store) {
						$subquery->whereIn('pos_pull_headers.stores_id',CRUDBooster::myStore())
							->orWhere('pos_pull_headers.wh_to',$store->pos_warehouse);
					});;  
				}

				elseif(CRUDBooster::myPrivilegeName() == "Online WSDM") {
					$query->whereIn('pos_pull_headers.stores_id', CRUDBooster::myStore());   
				}
				
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Audit","Inventory Control","Merch","Operation Manager"])){
				    
				}
				else{
					$query->where(function($subquery) use ($store) {
                        $subquery->whereIn('pos_pull_headers.stores_id',CRUDBooster::myStore())
							->orWhere('pos_pull_headers.wh_to',$store->pos_warehouse);
                    });
				}
				
			}
	            
	    }
		   
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
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
			if($column_index == 6){
				if($column_value == "Logistics"){
					$column_value = '<span class="label label-default">LOGISTICS</span>';
				}
				elseif($column_value == "Hand Carry"){
					$column_value = '<span class="label label-primary">HAND CARRY</span>';
				}
			}
	    }
		

		public function printPicklist($st_number)
		{
			$this->cbLoader();
			$data = array();
			$data['page_title'] = 'STS Details';
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

			$this->cbView("stock-transfer.print-picklist", $data);
			
		}
		
		public function getPrint($st_number)
		{
			$this->cbLoader();
			$data = array();
			$data['page_title'] = 'STS Details';
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

			$this->cbView("stock-transfer.print", $data);
		}

		public function getDetail($st_number)
		{
			if(!CRUDBooster::isRead() && $this->global_privilege == false || $this->button_detail == false) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

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
			
			$this->cbView("stock-transfer.detail", $data);
		}

		public function exportSTS(Request $request)
		{
			$store = DB::table('stores')->where('id', CRUDBooster::myStore())->first();
			ini_set('memory_limit', '-1');

			$sts_item = DB::table('pos_pull_headers')->select(
				'pos_pull_headers.st_document_number as st_number',
				'pos_pull_headers.received_st_number',
				'reason.pullout_reason',
				'pos_pull.item_code as digits_code',
				'items.upc_code',
				'items.item_description',
				'stores.bea_so_store_name as source',
				'stores1.bea_so_store_name as destination',
				'pos_pull.quantity as sts_quantity',
				'transport_types.transport_type as transport_by',
				'pos_pull_headers.scheduled_at',
				'scheduled_log.name as scheduled_by',
				'pos_pull_headers.created_date as created_date',
				'pos_pull_headers.received_st_date as received_date',
				'pos_pull_headers.status')
			->leftjoin('pos_pull', 'pos_pull_headers.id','=', 'pos_pull.pos_pull_header_id')
			->leftJoin('items', 'pos_pull.item_code', '=', 'items.digits_code')
			->leftJoin('transport_types', 'pos_pull_headers.transport_types_id', '=', 'transport_types.id')
			->leftJoin('cms_users as scheduled_log', 'pos_pull_headers.scheduled_by', '=', 'scheduled_log.id')
			->leftJoin('reason', 'pos_pull_headers.reason_id', '=', 'reason.id')
			->leftJoin('stores as stores', 'pos_pull_headers.stores_id', '=', 'stores.id')
			->leftJoin('stores as stores1', 'pos_pull_headers.stores_id_destination', '=', 'stores1.id');
			
			if(!empty(\Request::get('filter_column'))) {
				$filter_column = \Request::get('filter_column');
				$sts_item->where(function($w) use ($filter_column) {
					if(is_array($filter_column)){
						foreach((array)$filter_column as $key=>$fc) {

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
					}
				});
				if(is_array($filter_column)){
					foreach((array)$filter_column as $key=>$fc) {
						$value = @$fc['value'];
						$type  = @$fc['type'];
						$sorting = @$fc['sorting'];

						if($sorting!='') {
							if($key) {
								$sts_item->orderby($key,$sorting);
								$filter_is_orderby = true;
							}
						}

						if ($type=='between') {
							if($key && $value) $sts_item->whereBetween($key,$value);
						}

						else {
							continue;
						}
					}
				}
			}
			if(!CRUDBooster::isSuperadmin() && !in_array(CRUDBooster::myPrivilegeName(), ["Audit","Inventory Control","Merch"])){
				if (in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
					$sts_item->where('pos_pull_headers.transport_types_id',1);
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Approver","Franchise Approver"])){
					//get approval matrix
					$approvalMatrix = ApprovalMatrix::where('approval_matrix.cms_users_id', CRUDBooster::myId())->get();
				
					$approval_array = array();
					foreach($approvalMatrix as $matrix){
						array_push($approval_array, $matrix->store_list);
					}
					$approval_string = implode(",",$approval_array);
					$storeList = array_map('intval',explode(",",$approval_string));

					//compare the store_list of approver to purchase header store_id
					$sts_item->whereIn('pos_pull_headers.stores_id', array_values((array)$storeList));
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Online Ops","Retail Ops","Franchise Ops","Online Viewer"])){
					if(empty($store)){
						$sts_item->where('pos_pull_headers.channel_id', CRUDBooster::myChannel());
					}
					else{
						$sts_item->where('pos_pull_headers.channel_id', CRUDBooster::myChannel())
						->where(function($subquery) use ($store) {
							$subquery->whereIn('pos_pull_headers.stores_id',CRUDBooster::myStore())->orWhere('pos_pull_headers.wh_to',$store->pos_warehouse);
						});
					}
				}
				elseif(CRUDBooster::myPrivilegeName() == "Rtl Fra Ops") {
					$sts_item->whereIn('pos_pull_headers.channel_id', [1,2]);
				}
				elseif(CRUDBooster::myPrivilegeName() == "Franchise Viewer") {
					$sts_item->where(function($subquery) use ($store) {
						$subquery->where('stores.bea_so_store_name',$store->bea_so_store_name)
						->orWhere('stores1.bea_so_store_name',$store->bea_so_store_name);
					});
				}
				elseif(CRUDBooster::myPrivilegeName() == "Reports") {
					$sts_item->whereIn('pos_pull_headers.channel_id', [1,2,4]);
				}
				else{
					
					$sts_item->where(function($subquery) use ($store) {
						$subquery->where('stores.bea_so_store_name',$store->bea_so_store_name)
						->orWhere('stores1.bea_so_store_name',$store->bea_so_store_name);
					});
				}
			}
			$sts_item->orderBy('pos_pull_headers.st_document_number', 'asc');
			$stsItems = $sts_item->get();
			
			$headings = ['ST #',
				'RECEIVED ST #',
				'REASON',
				'DIGITS CODE',
				'UPC CODE',
				'ITEM DESCRIPTION',
				'SOURCE',
				'DESTINATION',
				'QTY',
				'TRANSPORT BY',
				'SCHEDULED DATE/BY',
				'CREATED DATE',
				'RECEIVED DATE',
				'STATUS'
			];

			foreach($stsItems as $item) {
				$items_array[] = [
					$item->st_number,
					$item->received_st_number,
					$item->pullout_reason,
					$item->digits_code,	
					'="'.$item->upc_code.'"',			
					$item->item_description,	
					$item->source,
					$item->destination,
					$item->sts_quantity,
					$item->transport_by,
					(!empty($item->scheduled_at)) ? $item->scheduled_at.' - '.$item->scheduled_by : '',
					$item->created_date,
					$item->received_date,
					$item->status
				];
			}

			ini_set('max_execution_time', 0);
			$filename = 'Export STS - '.date("Ymd H:i:sa");
			// self::exportExcel($items_array, $filename);
			Excel::create('Export STS - '.date("Ymd H:i:sa"), function($excel) use ($headings, $items_array){
				$excel->sheet('sts', function($sheet) use ($headings, $items_array){
					// Set auto size for sheet
					$sheet->setAutoSIZE(true);
					$sheet->setColumnFormat(array(
						'D' => '@',
					));
					
					$sheet->fromArray($items_array, null, 'A1', false, false);
					$sheet->prependRow(1, $headings);
					$sheet->row(1, function($row) {
						$row->setBackground('#FFFF00');
						$row->setAlignment('center');
					});
					
				});
			})->export('xlsx');
		}

		public function exportSTSSerialized(Request $request)
		{
			$store = DB::table('stores')->where('id', CRUDBooster::myStore())->first();
			ini_set('memory_limit', '-1');

			$sts_item = DB::table('pos_pull')->select(
				'pos_pull_headers.st_document_number as st_number',
				'pos_pull_headers.received_st_number',
				'reason.pullout_reason',
				'pos_pull.item_code as digits_code',
				'items.upc_code',
				'items.item_description',
				'stores.bea_so_store_name as source',
				'stores1.bea_so_store_name as destination',
				'pos_pull.quantity as sts_quantity',
				'serials.serial_number',
				'transport_types.transport_type as transport_by',
				'pos_pull_headers.scheduled_at',
				'scheduled_log.name as scheduled_by',
				'pos_pull_headers.created_at as created_date',
				'pos_pull_headers.received_st_date as received_date',
				'pos_pull_headers.status')
			->leftjoin('pos_pull_headers', 'pos_pull.pos_pull_header_id','pos_pull_headers.id')
			->leftJoin('items', 'pos_pull.item_code', '=', 'items.digits_code')
			->leftJoin('transport_types', 'pos_pull_headers.transport_types_id', '=', 'transport_types.id')
			->leftJoin('cms_users as scheduled_log', 'pos_pull_headers.scheduled_by', '=', 'scheduled_log.id')
			->leftJoin('reason', 'pos_pull_headers.reason_id', '=', 'reason.id')
			->leftJoin('serials', 'pos_pull.id', '=', 'serials.pos_pull_id')
			->leftJoin('stores as stores', 'pos_pull_headers.stores_id', '=', 'stores.id')
			->leftJoin('stores as stores1', 'pos_pull_headers.stores_id_destination', '=', 'stores1.id');
			
			if(!\Request::get('filter_column')) {
				$filter_column = \Request::get('filter_column');
				$sts_item->where(function($w) use ($filter_column) {
					if(is_array($filter_column)){
						foreach((array)$filter_column as $key=>$fc) {

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
					}
				});
				if(is_array($filter_column)){
					foreach((array)$filter_column as $key=>$fc) {
						$value = @$fc['value'];
						$type  = @$fc['type'];
						$sorting = @$fc['sorting'];

						if($sorting!='') {
							if($key) {
								$sts_item->orderby($key,$sorting);
								$filter_is_orderby = true;
							}
						}

						if ($type=='between') {
							if($key && $value) $sts_item->whereBetween($key,$value);
						}

						else {
							continue;
						}
					}
				}
			}
			if(!CRUDBooster::isSuperadmin() && !in_array(CRUDBooster::myPrivilegeName(), ["Audit","Inventory Control","Merch"])){
				if (in_array(CRUDBooster::myPrivilegeName(),["LOG TM","LOG TL"])) {
					$sts_item->where('pos_pull_headers.transport_types_id',1);
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Approver","Franchise Approver"])){
					//get approval matrix
					$approvalMatrix = ApprovalMatrix::where('approval_matrix.cms_users_id', CRUDBooster::myId())->get();
				
					$approval_array = array();
					foreach($approvalMatrix as $matrix){
						array_push($approval_array, $matrix->store_list);
					}
					$approval_string = implode(",",$approval_array);
					$storeList = array_map('intval',explode(",",$approval_string));

					//compare the store_list of approver to purchase header store_id
					$sts_item->whereIn('pos_pull_headers.stores_id', array_values((array)$storeList));
				}
				elseif(in_array(CRUDBooster::myPrivilegeName(), ["Online Ops","Retail Ops","Franchise Ops","Online Viewer"])){
					if(empty($store)){
						$sts_item->where('pos_pull_headers.channel_id', CRUDBooster::myChannel());
					}
					else{
						$sts_item->where('pos_pull_headers.channel_id', CRUDBooster::myChannel())
						->where(function($subquery) use ($store) {
							$subquery->whereIn('pos_pull_headers.stores_id',CRUDBooster::myStore())->orWhere('pos_pull_headers.wh_to',$store->pos_warehouse);
						});
					}
				}
				elseif(CRUDBooster::myPrivilegeName() == "Rtl Fra Ops") {
					$sts_item->whereIn('pos_pull_headers.channel_id', [1,2]);
				}
				elseif(CRUDBooster::myPrivilegeName() == "Franchise Viewer") {
					$sts_item->where(function($subquery) use ($store) {
						$subquery->where('stores.bea_so_store_name',$store->bea_so_store_name)
						->orWhere('stores1.bea_so_store_name',$store->bea_so_store_name);
					});
				}
				elseif(CRUDBooster::myPrivilegeName() == "Reports") {
					$sts_item->whereIn('pos_pull_headers.channel_id', [1,2,4]);
				}
				else {
					$sts_item->where(function($subquery) use ($store) {
						$subquery->where('stores.bea_so_store_name',$store->bea_so_store_name)
						->orWhere('stores1.bea_so_store_name',$store->bea_so_store_name);
					});
				}
			}
			$sts_item->orderBy('pos_pull_headers.st_document_number', 'asc');
			$stsItems = $sts_item->get();
			
			$items_array[] = array('ST #',
				'RECEIVED ST #',
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
				'CREATED DATE',
				'RECEIVED DATE',
				'STATUS');

			foreach($stsItems as $item) {
				$items_array[] = array(
					'ST #' => $item->st_number,
					'RECEIVED ST #' => $item->received_st_number,
					'REASON' => $item->pullout_reason,
					'DIGITS CODE' => $item->digits_code,
					'UPC CODE' => '="' . $item->upc_code . '"',
					'ITEM DESCRIPTION' => $item->item_description,
					'SOURCE' => $item->source,
					'DESTINATION' => $item->destination,
					'QTY' => (empty($item->serial_number)) ? $item->sts_quantity : 1,
					'SERIAL #' => $item->serial_number,
					'TRANSPORT BY' => $item->transport_by,
					'SCHEDULED DATE/BY' => (!empty($item->scheduled_at)) ? $item->scheduled_at . ' - ' . $item->scheduled_by : '',
					'CREATED DATE' => $item->created_date,
					'RECEIVED DATE' => $item->received_date,
					'STATUS' => $item->status
				);
			}

			ini_set('max_execution_time', 0);
			$filename = 'Export STS with Serial- '.date("Ymd H:i:sa");
			self::exportExcel($items_array, $filename);
			// Excel::create('Export STS with Serial- '.date("Ymd H:i:sa"), function($excel) use ($headings,$items_array){
			// 	$excel->sheet('sts-serial', function($sheet) use ($headings,$items_array){
			// 		// Set auto size for sheet
			// 		$sheet->setAutoSIZE(true);
			// 		$sheet->setColumnFormat(array(
			// 			'D' => '@',
			// 		));
					
			// 		$sheet->fromArray($items_array, null, 'A1', false, false);
			// 		$sheet->prependRow(1, $headings);
			// 		$sheet->row(1, function($row) {
			// 			$row->setBackground('#FFFF00');
			// 			$row->setAlignment('center');
			// 		});
					
			// 	});
			// })->export('xlsx');
		}

		public function exportExcel($data,$filename){
			ini_set('max_execution_time', 0);
			ini_set('memory_limit', '4000M');
			try {
				$spreadSheet = new Spreadsheet();
				$spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
				$spreadSheet->getActiveSheet()->fromArray($data);
				$excelWriter = new Xlsx($spreadSheet);
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
				header('Cache-Control: max-age=0');
				ob_end_clean();
				$excelWriter->save('php://output');
				exit();
			} catch (Exception $e) {
				Log::error($e->getMessage());
				return;
			}
		}

	}
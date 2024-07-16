<?php 

	namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use App\Http\Controllers\POSPushController;
	use App\Item;
	use App\OnhandQty;

	class AdminItemsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "digits_code";
			$this->limit = "20";
			$this->orderby = "digits_code,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "items";
			# END CONFIGURATION DO NOT REMOVE THIS LINE
            $distriSubs = DB::table('distri_subinventories')->where('status','ACTIVE')->orderBy('subinventory','ASC')->get();
			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Beach Item Code","name"=>"bea_item_id"];
			$this->col[] = ["label"=>"Digits Code","name"=>"digits_code"];
			$this->col[] = ["label"=>"UPC Code","name"=>"upc_code"];
			$this->col[] = ["label"=>"Item Description","name"=>"item_description"];
			$this->col[] = ["label"=>"Brand","name"=>"brand"];
			$this->col[] = ["label"=>"Serial","name"=>"has_serial"];
			$this->col[] = ["label"=>"RTL Reserve Qty","name"=>"reserve_qty"];
			$this->col[] = ["label"=>"Lazada Reserve Qty","name"=>"lazada_reserve_qty"];
			$this->col[] = ["label"=>"Shopee Reserve Qty","name"=>"shopee_reserve_qty"];
			
			foreach($distriSubs as $dSub){
			    $this->col[] = ["label"=>ucwords(strtolower($dSub->subinventory))." Reserve Qty","name"=>strtolower(str_replace(" ","_", $dSub->subinventory))."_reserve_qty"];
			}
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Beach Item Code','name'=>'bea_item_id','type'=>'select2','validation'=>'required|min:1|max:255','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Digits Code','name'=>'digits_code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'UPC Code','name'=>'upc_code','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'UPC Code2','name'=>'upc_code2','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'UPC Code3','name'=>'upc_code3','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'UPC Code4','name'=>'upc_code4','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'UPC Code5','name'=>'upc_code5','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Item Description','name'=>'item_description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Brand','name'=>'brand','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Serial','name'=>'has_serial','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-4'];
			if(CRUDBooster::getCurrentMethod() == 'getDetail' && CRUDBooster::isSuperadmin()){
			    
			    $this->form[] = ['label'=>'Store Cost','name'=>'store_cost','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-4'];
			
			}
			$this->form[] = ['label'=>'RTL Reserve Qty','name'=>'reserve_qty','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Lazada Reserve Qty','name'=>'lazada_reserve_qty','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-4'];
			$this->form[] = ['label'=>'Shopee Reserve Qty','name'=>'shopee_reserve_qty','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-4'];
			foreach($distriSubs as $dSub){
			    $this->form[] = ["label"=>ucwords(strtolower($dSub->subinventory))." Reserve Qty","name"=>strtolower(str_replace(" ","_", $dSub->subinventory))."_reserve_qty",'type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-4'];
			}
			
	        $this->button_selected = array();
            if(CRUDBooster::isSuperadmin())
	        {
				$this->button_selected[] = ['label'=>'Set Item Serialize',
											'icon'=>'fa fa-check-circle',
											'name'=>'set_item_serialize'];
											
				$this->button_selected[] = ['label'=>'Set Item General',
											'icon'=>'fa fa-check-circle-o',
											'name'=>'set_item_general'];

				$this->button_selected[] = ['label'=>'Create POS Item',
											'icon'=>'fa fa-check-circle-o',
											'name'=>'create_pos_item'];

				$this->button_selected[] = ['label'=>'Sync Item RTL Qty',
											'icon'=>'fa fa-check-circle-o',
											'name'=>'sync_rtl_qty'];
	        }

	        $this->index_statistic = array();
            $this->index_statistic[] = ['label'=>'Total SKUs','count'=>DB::table($this->table)->count('digits_code'),'icon'=>'fa fa-pie-chart','color'=>'blue','width'=>'col-sm-6'];
	        
	    }

	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
            if($button_name == 'set_item_serialize') {
            	Item::whereIn('id',$id_selected)->update([ 'has_serial'=> 1 ]);
            	
            }
            else if($button_name == 'set_item_general') {
            	Item::whereIn('id',$id_selected)->update([ 'has_serial'=> 0 ]);
            	
            } 
			else if($button_name == 'create_pos_item') {
            	$items = Item::whereIn('id',$id_selected)->get();
            	foreach ($items as $key => $value) {
					(new POSPushController)->posCreateItem(
						$value->digits_code,
						$value->upc_code,
						$value->item_description,
						$value->current_srp,
						$value->store_cost,
						$value->has_serial);
				}
            } 
			else if($button_name == 'sync_rtl_qty'){
				$items = Item::whereIn('id',$id_selected)->get(['id','digits_code']);
				foreach ($items as $key => $value) {
					try {
						$rtlQty = OnhandQty::getOnhand()->where('mtl_system_items_b.segment1',$value->digits_code)->first();
						$item = Item::find($value->id);
						$item->reserve_qty = $rtlQty->quantity;
						$item->save();
					} catch (\Exception $e) {
						\Log::error('Error!'.$e->getMessage());
					}
				}
			}
			
	    }

	}
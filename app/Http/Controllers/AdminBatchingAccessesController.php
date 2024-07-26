<?php namespace App\Http\Controllers;

use App\BatchingAccess;
use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminBatchingAccessesController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,asc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "batching_accesses";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Batching","name"=>"batching_id"];
			$this->col[] = ["label"=>"Start","name"=>"start"];
			$this->col[] = ["label"=>"End","name"=>"end"];
			$this->col[] = ["label"=>"Status","name"=>"status"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created Date","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated Date","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Batch #','name'=>'batching_id','type'=>'number','validation'=>'required','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'Start','name'=>'start','type'=>'time','validation'=>'required|date_format:H:i:s','width'=>'col-sm-5'];
			$this->form[] = ['label'=>'End','name'=>'end','type'=>'time','validation'=>'required|date_format:H:i:s','width'=>'col-sm-5'];
			if(CRUDBooster::getCurrentMethod() == 'getEdit' || CRUDBooster::getCurrentMethod() == 'postEditSave' || CRUDBooster::getCurrentMethod() == 'getDetail') {
				$this->form[] = ['label'=>'Status','name'=>'status','type'=>'select','validation'=>'required','width'=>'col-sm-5','dataenum'=>'ACTIVE;INACTIVE'];
			}
			
	        $this->button_selected = array();
			if(CRUDBooster::isUpdate()) {
				$this->button_selected[] = ["label"=>"Set Status ACTIVE ","icon"=>"fa fa-check-circle","name"=>"set_status_ACTIVE"];
				$this->button_selected[] = ["label"=>"Set Status INACTIVE","icon"=>"fa fa-times-circle","name"=>"set_status_INACTIVE"];
			}	        
	        
	    }
		
	    public function actionButtonSelected($id_selected,$button_name) {
	        switch ($button_name) {
				case 'set_status_ACTIVE':
					BatchingAccess::whereIn('id',$id_selected)->update([
						'status'=>'ACTIVE', 
						'updated_by' => CRUDBooster::myId(),
						'updated_at' => date('Y-m-d H:i:s')
					]);
					break;
				case 'set_status_INACTIVE':
					BatchingAccess::whereIn('id',$id_selected)->update([
						'status'=>'INACTIVE', 
						'updated_by' => CRUDBooster::myId(),
						'updated_at' => date('Y-m-d H:i:s')
					]);
					break;
				default:
					# code...
					break;
			} 
	            
	    }
		
	    public function hook_before_add(&$postdata) {        
	        //Your code here
			$postdata['created_by'] = CRUDBooster::myId();
			$postdata['created_at'] = date('Y-m-d H:i:s');
	    }
		
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here
			$postdata['updated_by'] = CRUDBooster::myId();
			$postdata['updated_at'] = date('Y-m-d H:i:s');
	    }

	}
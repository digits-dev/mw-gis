<?php namespace App\Http\Controllers;

use App\BatchingAccess;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;
use CRUDbooster;
use Excel;
use Illuminate\Support\Facades\Log;

class AdminCmsUsersController extends \crocodicstudio\crudbooster\controllers\CBController {


	public function cbInit() {
		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->table               = 'cms_users';
		$this->primary_key         = 'id';
		$this->title_field         = "name";
		$this->button_action_style = 'button_icon';	
		$this->button_import 	   = false;	
		$this->button_export 	   = true;	
		# END CONFIGURATION DO NOT REMOVE THIS LINE
	
		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = array();
		$this->col[] = array("label"=>"Name","name"=>"name");
		$this->col[] = array("label"=>"Email","name"=>"email");
		$this->col[] = array("label"=>"Privilege","name"=>"id_cms_privileges","join"=>"cms_privileges,name");
		$this->col[] = array("label"=>"Channel","name"=>"channel_id","join"=>"channel,channel_description");
		$this->col[] = array("label"=>"Store","name"=>"stores_id");
		$this->col[] = array("label"=>"Batch","name"=>"batch_id");
		$this->col[] = array("label"=>"Photo","name"=>"photo","image"=>1);	
		$this->col[] = array("label"=>"Status","name"=>"status");
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = array(); 		
		$this->form[] = array("label"=>"Name","name"=>"name",'required'=>true,'validation'=>'required|min:3','readonly'=>(CRUDBooster::isSuperadmin()) ? false : true);
		$this->form[] = array("label"=>"Email","name"=>"email",'required'=>true,'type'=>'email','validation'=>'required|email|unique:cms_users,email,'.CRUDBooster::getCurrentId(),'readonly'=>(CRUDBooster::isSuperadmin()) ? false : true);		
		$this->form[] = array("label"=>"Photo","name"=>"photo","type"=>"upload","help"=>"Recommended resolution is 200x200px",'validation'=>'image|max:1000');//'required'=>CRUDBooster::isSuperadmin()?false:true											
		$this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name",'required'=>true);
		if(CRUDBooster::isSuperadmin()){
			$this->form[] = array("label"=>"Channel","name"=>"channel_id","type"=>"select2","datatable"=>"channel,channel_description",'datatable_where'=>"status='ACTIVE'",'required'=>CRUDBooster::isSuperadmin() ? false : true);
			$this->form[] = array("label"=>"Store","name"=>"stores_id","type"=>"check-box","datatable"=>"stores,bea_so_store_name",'datatable_where'=>"status=%27ACTIVE%27",'required'=>CRUDBooster::isSuperadmin() ? false : true, 'parent_select'=>'channel_id');						
		}
		$this->form[] = array("label"=>"Password","name"=>"password","type"=>"password","help"=>"Please leave empty if not changed");
		# END FORM DO NOT REMOVE THIS LINE
		
		$this->index_button = array();
        if(CRUDBooster::getCurrentMethod() == 'getIndex') {
			if(CRUDBooster::isSuperadmin()){
				$this->index_button[] = [
					"title"=>"Upload User Accounts",
					"label"=>"Upload User Accounts",
					"icon"=>"fa fa-upload",
					"url"=>CRUDBooster::mainpath('users-upload')];
			}
		}
		
		$this->button_selected = array();
		if(CRUDBooster::isUpdate()) {
			$this->button_selected[] = ["label"=>"Set Status ACTIVE ","icon"=>"fa fa-check-circle","name"=>"set_status_ACTIVE"];
			$this->button_selected[] = ["label"=>"Set Status INACTIVE","icon"=>"fa fa-times-circle","name"=>"set_status_INACTIVE"];
			$this->button_selected[] = ["label"=>"Reset Password","icon"=>"fa fa-refresh","name"=>"reset_password"];
			$this->button_selected[] = ["label"=>"Reset Batch","icon"=>"fa fa-refresh","name"=>"reset_batch"];
			$batches = BatchingAccess::active()->get();

			foreach ($batches as $key => $value) {
				$this->button_selected[] = ["label"=>"Tag Batch {$value->batching_id}","icon"=>"fa fa-check-circle","name"=>"tag_batch_{$value->batching_id}"];
			}
		}

		$this->script_js[] ='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js';
				
	}
	
	public function actionButtonSelected($id_selected,$button_name) {
		//Your code here
		$batches = BatchingAccess::active()->get();

		foreach ($batches as $key => $value) {
			if($button_name == "tag_batch_{$value->batching_id}"){
				DB::table('cms_users')->whereIn('id',$id_selected)->update([
					'batch_id' => $value->batching_id, 
					'updated_at' => date('Y-m-d H:i:s')
				]);
			}
		}
		switch ($button_name) {
			case 'set_status_ACTIVE':
				DB::table('cms_users')->whereIn('id',$id_selected)->update([
					'status'=>'ACTIVE', 
					'updated_at' => date('Y-m-d H:i:s')
				]);
				break;
			case 'set_status_INACTIVE':
				DB::table('cms_users')->whereIn('id',$id_selected)->update([
					'status'=>'INACTIVE', 
					'updated_at' => date('Y-m-d H:i:s')
				]);
				break;
			case 'reset_password':
				DB::table('cms_users')->whereIn('id',$id_selected)->update([
					'password'=>bcrypt('qwerty'),
					'updated_at' => date('Y-m-d H:i:s')
				]);
				break;
			case 'reset_batch':
				DB::table('cms_users')->whereIn('id',$id_selected)->update([
					'batch_id' => null,
					'updated_at' => date('Y-m-d H:i:s')
				]);
				break;
			default:
				# code...
				break;
		}  
	}

	public function getProfile() {			

		$this->button_addmore = FALSE;
		$this->button_cancel  = FALSE;
		$this->button_show    = FALSE;			
		$this->button_add     = FALSE;
		$this->button_delete  = FALSE;	
		$this->hide_form 	  = ['id_cms_privileges'];

		$data['page_title'] = trans("crudbooster.label_button_profile");
		$data['row']        = CRUDBooster::first('cms_users',CRUDBooster::myId());		
		$this->cbView('crudbooster::default.form',$data);				
	}
	
	public function hook_query_index(&$query) {
        //Your code here
        if(!CRUDBooster::isSuperadmin()) {
        	if(CRUDBooster::myPrivilegeName() == 'ADMIN'){
        		$query->where('cms_users.id_cms_privileges','!=','1');
        	}
        	else{
        		$query->where('cms_users.id',CRUDBooster::myId());
        	}
        }    
	}

	public function hook_before_add(&$postdata) {        
	    
	    if($postdata['photo'] == '' || $postdata['photo'] == NULL) {
	    	$postdata['photo'] = 'uploads/mrs-avatar.png';
		}
		
		$storeData = array();
		$storeList = json_encode($postdata['stores_id'], true);
		$storeArray = explode(",", $storeList);

		foreach ($storeArray as $key => $value) {
			$storeData[$key] = preg_replace("/[^0-9]/","",$value);
		}

		if(!empty($postdata['stores_id'])){
			$postdata['stores_id'] = implode(",", $storeData);
		}
		
		$postdata['status'] = 'ACTIVE';
	}

	public function hook_before_edit(&$postdata,$id) {
		if(CRUDBooster::isSuperadmin() || CRUDBooster::myPrivilegeName() == "Admin") {
            
    		$storeData = array();
    		$storeList = json_encode($postdata['stores_id'], true);
    		$storeArray = explode(",", $storeList);
    
    		foreach ($storeArray as $key => $value) {
    			$storeData[$key] = preg_replace("/[^0-9]/","",$value);
    		}
			if(!empty($postdata['stores_id'])){
				$postdata['stores_id'] = implode(",", $storeData);
			}
        }
	}
	
	public function uploadUsersTemplate() {
		Excel::create('user-account-upload-'.date("Ymd").'-'.date("h.i.sa"), function ($excel) {
			$excel->sheet('useraccount', function ($sheet) {
				$sheet->row(1, array('USER NAME', 'EMAIL', 'PRIVILEGE', 'CHANNEL', 'STORE NAME'));
				$sheet->row(2, array('John Doe', 'johndoe@digits.ph','Requestor','Retail','BTB CENTURY'));
			});
		})->download('csv');
	}

	public function uploadUsers() {
	    if(!CRUDBooster::isSuperadmin()) {    
			CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
		}
		$data['page_title']= 'Users Account Upload';
		return view('users.users_upload', $data)->render();
	}

	public function usersUpload(Request $request) {
		$file = $request->file('import_file');
		
		$validator = \Validator::make(
			[
				'file' => $file,
				'extension' => strtolower($file->getClientOriginalExtension()),
			],
			[
				'file' => 'required',
				'extension' => 'required|in:csv',
			]
		);

		if ($validator->fails()) {
			CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_user_data_failed"), 'danger');
		}

		if (Input::hasFile('import_file')) {
			$path = Input::file('import_file')->getRealPath();
			
			$csv = array_map('str_getcsv', file($path));
			$dataExcel = Excel::load($path, function($reader) {
            })->get();
			
			$unMatch = [];
			$header = array('USER NAME', 'EMAIL', 'PRIVILEGE', 'CHANNEL', 'STORE NAME');

			for ($i=0; $i < sizeof($csv[0]); $i++) {
				if (! in_array($csv[0][$i], $header)) {
					$unMatch[] = $csv[0][$i];
				}
			}

			if(!empty($unMatch)) {
				CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_user_data_failed"), 'danger');
			}
			
			$data = array();
			
			if(!empty($dataExcel) && $dataExcel->count()) {
				$cnt_success = 0;
				$cnt_fail = 0;
				
				foreach ($dataExcel as $key => $value) {
					$privilegeId = DB::table('cms_privileges')->where('name', $value->privilege)->value('id');
					$channelId = DB::table('channel')->where('channel_description', $value->channel)->value('id');
					$storeId = DB::table('stores')->where('bea_so_store_name', $value->store_name)->where('channel_id', $channelId)->value('id');
					if(!empty($privilegeId) && $privilegeId != 1 && !empty($storeId)){
						$data = [
						    'name'  =>  $value->user_name,
						    'channel_id'   => $channelId,
						    'stores_id' =>  $storeId,
						    'photo' => 'uploads/mrs-avatar.png',
						    'email' => $value->email,
						    'password' => bcrypt('qwerty'),
						    'id_cms_privileges' => $privilegeId,
							'status'    => 'ACTIVE',
							'created_at'    => date('Y-m-d H:i:s'),
						];
					}
					
					
					DB::beginTransaction();
					
					try {
						$isItemUpload = DB::table('cms_users')->updateOrInsert(['email' => $value->email], $data);
						DB::commit();
					} catch (\Exception $e) {
						Log::error($e->getMessage());
						DB::rollback();
					}

					if ($isItemUpload) {
						$cnt_success++;
                    }
                    else{
						$cnt_fail++;
                    }
				}

				if($cnt_fail == 0){
                    CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_upload_user_success", ['total_row'=>$dataExcel->count(),'success'=>$cnt_success,'fail'=>$cnt_fail]), 'success');
                }
                else{
                    CRUDBooster::redirect(CRUDBooster::mainpath(), trans("crudbooster.alert_upload_user_success", ['total_row'=>$dataExcel->count(),'success'=>$cnt_success,'fail'=>$cnt_fail]), 'success');
                }
			}
		}
	}
	
	public function hook_row_index($column_index,&$column_value) {	        
		//Your code here
		$col_values = '';
		if($column_index == 5){
			$storeLists = $this->storeListing($column_value);
			
			foreach ($storeLists as $value) {
				$col_values .= '<span stye="display: block;" class="label label-info">'.$value.'</span><br>';
			}
			$column_value = $col_values;
		}
	}

	public static function storeListing($ids) {
		$stores = explode(",", $ids);
		return DB::table('stores')->whereIn('id', $stores)->pluck('bea_so_store_name');
	}
}

<?php 
namespace App\Http\Controllers;

use App\ApprovalMatrix;
use DB;
use Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CBHook extends Controller {

	/*
	| --------------------------------------
	| Please note that you should re-login to see the session work
	| --------------------------------------
	|
	*/
	public function afterLogin() {
		$users = DB::table('cms_users')->where("email", request('email'))->first();

        if($users->status == 'INACTIVE'){
			Session::flush();
			return redirect()->route('getLogin')->with('message', 'The user does not exist!');
		}

        if (Hash::check(request('password'), $users->password)) {

            $priv = DB::table("cms_privileges")->where("id", $users->id_cms_privileges)->first();
            $store_name = DB::table("stores")->where("id", $users->stores_id)->first();
			
			Session::put('admin_bulk_receiving',explode(",",$store_name->allowed_bulk_receiving));
			Session::put('admin_customer_type',$store_name->customer_type_id);
			Session::put('admin_store',explode(",",$users->stores_id));
			Session::put('admin_store_mo_name',$store_name->bea_mo_store_name);
			Session::put('admin_store_so_name',$store_name->bea_so_store_name);
			Session::put('admin_pos_warehouse',$store_name->pos_warehouse);
			Session::put('admin_channel',$users->channel_id);
			Session::put('admin_batch_id',$users->batch_id);

			if($priv->name == "Approver") {
                $approvalMatrix = ApprovalMatrix::where('approval_matrix.cms_users_id', $users->id)->get();
				
				$storeList_array = [];
				foreach ($approvalMatrix as $matrix) {
					$storeList_array = array_merge($storeList_array, explode(',', $matrix->store_list));
				}
				$storeList = '(' . implode(',', $storeList_array) . ')';
                Session::put('approval_store_ids', $storeList);
            }
			else if($priv->name == "Warehouse Online") {
				$storeListOnlWh = '(' . $users->stores_id . ')';
                Session::put('onlwh_store_ids', $storeListOnlWh);
			}
			$requestorStoreList = '(' . $users->stores_id . ')';
			Session::put('admin_requestor_store', $requestorStoreList);
        }
	}
}
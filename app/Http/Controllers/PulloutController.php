<?php

namespace App\Http\Controllers;

use App\Pullout;
use Carbon\Carbon;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class PulloutController extends Controller
{
    public function getApprovePullouts() {
        $posts = Request::all();

        $debug_mode_message = 'You are in debug mode !';

        $result['api_status']  = 0;
        $result['api_message'] = 'Sorry this API endpoint is no longer available or has been changed. Please make sure endpoint is correct.';
        $result['api_http']    = 401;


        //check data
        Log::debug($posts);

        //update result
        $result['api_status']  = 1;
		$result['api_message'] = 'success';
		$result['api_http'] = $result['api_http'] === 401 ? $result['api_http'] : 200;

		if ( CRUDBooster::getSetting( 'api_debug_mode' ) == 'true' ) {
			$result['api_authorization'] = $debug_mode_message;
		}

		return response()->json( $result, 200 );
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allowedUserAgent = config('crudbooster.API_USER_AGENT_ALLOWED');
        $user_agent = Request::header('User-Agent');
        $time       = Request::header('X-Authorization-Time');


        if($allowedUserAgent && count($allowedUserAgent)) {
            $userAgentValid = false;
            foreach($allowedUserAgent as $a) {
                if(stripos($user_agent, $a)!==FALSE) {
                    $userAgentValid = true;
                    break;
                }
            }
            if($userAgentValid==false) {
                $result['api_status']   = false;
                $result['api_message']  = "THE DEVICE AGENT IS INVALID";
                $res = response()->json($result,200);
                $res->send();
                exit;
            }
        }

        if(CRUDBooster::getSetting('api_debug_mode') == 'false') {
                
            $result = array();
            $validator = Validator::make(
                [   
                
                'X-Authorization-Token' =>Request::header('X-Authorization-Token'),
                'X-Authorization-Time'  =>Request::header('X-Authorization-Time'),
                'useragent'             =>Request::header('User-Agent')
                ],          
                [
                
                'X-Authorization-Token' =>'required',
                'X-Authorization-Time'  =>'required',   
                'useragent'             =>'required'              
                ]
            );      
            
            if ($validator->fails()) 
            {
                $message = $validator->errors()->all();         
                $result['api_status'] = 0;
                $result['api_message'] = implode(', ',$message);            
                $res = response()->json($result,200);
                $res->send();
                exit;
            }

            $keys = DB::table('cms_apikey')->where('status','active')->pluck('screetkey');
            $server_token = array();
            $server_token_screet = array();
            foreach($keys as $key) {
                $server_token[] = md5( $key . $time . $user_agent );
                $server_token_screet[] = $key;
            }
        
            $sender_token = Request::header('X-Authorization-Token');

            if(!Cache::has($sender_token)) {
                if(!in_array($sender_token, $server_token)) {           
                    $result['api_status']   = false;
                    $result['api_message']  = "THE TOKEN IS NOT MATCH WITH SERVER TOKEN";
                    $res = response()->json($result,200);
                    $res->send();
                    exit;
                }
            }else{
                if(Cache::get($sender_token) != $user_agent) {
                    $result['api_status']   = false;
                    $result['api_message']  = "THE TOKEN IS ALREADY BUT NOT MATCH WITH YOUR DEVICE";
                    $res = response()->json($result,200);
                    $res->send();
                    exit;
                }
            }        

            $id = array_search($sender_token,$server_token);
            $server_screet = $server_token_screet[$id];
            DB::table('cms_apikey')->where('screetkey',$server_screet)->increment('hit');

            $expired_token = date('Y-m-d H:i:s',strtotime('+5 seconds'));
            Cache::put($sender_token,$user_agent,$expired_token);

        }
        
        return $next($request);
    }
}

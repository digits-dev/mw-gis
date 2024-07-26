<?php

namespace App\Http\Middleware;

use App\BatchingAccess;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Session;

class CheckAccessTime
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
        //get user details
        $batchUser = Session::get('admin_batch_id');
        $batchAccess = BatchingAccess::active()->where('batching_id',$batchUser)->first();
        // Get the current time
        $currentTime = Carbon::now()->format('H:i');

        // Check if current time is within the allowed range
        if (!is_null($batchUser) && !empty($batchAccess)) {
            if(($currentTime < $batchAccess->start || $currentTime > $batchAccess->end))
                // If the current time is not within the range, return an error response
                return response()->view('restricted');
                // return response()->json(['error' => 'Access not allowed at this time'], 403);
        }
        else{
            //allow bulk receiving
        }

        return $next($request);
    }
}

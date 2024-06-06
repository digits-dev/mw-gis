<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreOptions extends Controller
{
    public function getValues($parentId)
    {
        $data = [];
        $data['childOptions'] = DB::table('stores')->where('channel_id', $parentId)->get(); 
      
        return response()->json($data);
    }
}

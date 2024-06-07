<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PosPullLines extends Model
{
    protected $table = 'pos_pull';

    public function scopeGetItems($query, $st_id){
        return $query->where('pos_pull_header_id',$st_id)
        ->select('id','item_code','quantity','item_description');
    }

    public function scopeGetStQuantity($query, $st_id){
        return $query->where('pos_pull_header_id', $st_id)
        ->sum('quantity');
    }
}

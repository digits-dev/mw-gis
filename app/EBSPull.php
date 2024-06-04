<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EBSPull extends Model
{
    protected $table = 'ebs_pull';

    public function scopeGetDeliveryDetails($query, $dr_number){
        return $query->where('dr_number', $dr_number)->first();
    }
}

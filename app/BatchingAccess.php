<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BatchingAccess extends Model
{
    protected $table = 'batching_accesses';
    protected $guard = [];

    public function scopeActive($query){
        return $query->where('status', 'ACTIVE');
    }

}

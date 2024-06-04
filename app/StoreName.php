<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreName extends Model
{
    protected $table = 'stores';

    public function scopeGetMoCustomerName($query, $customer_name){
        return $query->where('bea_mo_store_name', $customer_name)->first();
    }

    public function scopeGetSoCustomerName($query, $customer_name){
        return $query->where('bea_so_store_name', $customer_name)->first();
    }
}

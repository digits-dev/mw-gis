<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreName extends Model
{
    protected $table = 'stores';
    protected $guarded = [];

    public function scopeGetStoreByName($query, $storeName){
        return $query->where('pos_warehouse', $storeName);
    }


    public function scopeGetMoCustomerName($query, $customer_name){
        return $query->where('bea_mo_store_name', $customer_name)->first();
    }

    public function scopeGetSoCustomerName($query, $customer_name){
        return $query->where('bea_so_store_name', $customer_name)->first();
    }

    public function scopeGetInboundTripStores($query, $pullouts, $transfers){
        return $query->whereIn('channel_id',[1,2])
            ->where('status','ACTIVE')
            ->where(function ($subquery) use ($pullouts, $transfers) {
                $subquery->whereIn('id',array_column($pullouts, 'stores_id'))
                ->orWhereIn('id',array_column($transfers, 'stores_id'));
            })->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name')
            ->orderBy('pos_warehouse_name','ASC');
    }

    public function scopeGetOutboundTripStores($query, $deliveries, $transfers){
        return $query->whereIn('channel_id',[1,2])
            ->where('status','ACTIVE')
            ->where(function ($subquery) use ($deliveries, $transfers) {
                $subquery->whereIn('id',array_column($deliveries, 'stores_id'))
                ->orWhereIn('id',array_column($transfers, 'stores_id_destination'));
            })->select('id','pos_warehouse','pos_warehouse_transit','pos_warehouse_name')
            ->orderBy('pos_warehouse_name','ASC');
        
    }
}

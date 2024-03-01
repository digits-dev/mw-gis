<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GisPull extends Model
{
    protected $table = 'gis_pulls';
    protected $fillable = [
        'ref_number', 
        'status_id', 
        'quantity_total', 
        'memo', 
        'stores_id', 
        'location_id_from',
        'sub_location_id_from',
        'location_from', 
        'stores_id_destination', 
        'location_id_to',
        'sub_location_id_to',
        'location_to', 
        'approved_by', 
        'approved_at', 
        'received_by', 
        'received_at', 
        'rejected_by', 
        'rejected_at', 
        'transport_types_id', 
        'reason_id', 
        'hand_carrier',
        'transfer_date',
        'created_at',
        'updated_at	',
        'deleted_at'
      
    ];

    public function scopeStGisHeader($query, $id){
       return $query->where('gis_pulls.id',$id)
                    ->leftjoin('reason','gis_pulls.reason_id','reason.id')
                    ->leftjoin('cms_users AS approver','gis_pulls.approved_by','approver.id')
                    ->leftjoin('cms_users AS receiver','gis_pulls.received_by','receiver.id')
                    ->leftjoin('cms_users AS rejector','gis_pulls.rejected_by','rejector.id')
                    ->leftjoin('cms_users AS scheduler','gis_pulls.schedule_by','scheduler.id')
                    ->leftJoin('transport_types', 'gis_pulls.transport_types_id', '=', 'transport_types.id')
                    ->select('gis_pulls.*',
                                'gis_pulls.id AS gp_id',
                                'reason.*',
                                'approver.name AS approver',
                                'receiver.name AS receiver',
                                'rejector.name AS rejector',
                                'scheduler.name AS scheduler',
                                'transport_types.transport_type'
                                )
                    ->first();
    }
}

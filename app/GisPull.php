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
        'location_from', 
        'stores_id_destination', 
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
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GisPullLines extends Model
{
    protected $table = 'gis_pull_lines';
    protected $fillable = [
        'gis_pull_id', 
        'item_code', 
        'item_description', 
        'quantity', 
        'created_at',
        'updated_at	',
      
    ];
}

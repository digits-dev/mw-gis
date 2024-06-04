<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OracleItemInterface extends Model
{
    protected $connection = 'oracle';  
    protected $table = 'MTL_SYSTEM_ITEMS_INTERFACE';
}

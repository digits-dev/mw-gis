<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;

class Delivery extends Eloquent
{
    protected $table = 'WSH_DELIVERY_DETAILS';
}

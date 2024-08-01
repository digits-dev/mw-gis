<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionStatus extends Model
{
    const PENDING = 'PENDING';
    const VOID = 'VOID';
    const FOR_SCHEDULE = 'FOR SCHEDULE';
    const FOR_RECEIVING = 'FOR RECEIVING';
    const CONFIRMED = 'CONFIRMED';
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PosPullHeader extends Model
{
    protected $table = 'pos_pull_headers';

    public function scopeGetDetails($query, $st_number){
        return $query->join('reason', 'pos_pull_headers.reason_id', '=', 'reason.id')
        ->leftJoin('transport_types', 'pos_pull_headers.transport_types_id', '=', 'transport_types.id')
        ->leftJoin('cms_users', 'pos_pull_headers.scheduled_by', '=', 'cms_users.id')
        ->where('st_document_number', $st_number)
        ->select('pos_pull_headers.*','reason.pullout_reason','transport_types.transport_type','cms_users.name as scheduled_by');
    }
}

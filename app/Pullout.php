<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pullout extends Model
{
    protected $table = 'pullout';
    protected $guarded = [];

    public function scopeGetDetailWithMoReason($query, $stNumber) {
        return $query->join('reason', 'pullout.reason_id', '=', 'reason.bea_mo_reason')
            ->leftJoin('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
            ->where('st_document_number', $stNumber)
            ->select('pullout.*','reason.pullout_reason','reason.id as reason_id','transport_types.transport_type');
    }

    public function scopeGetDetailWithSoReason($query, $stNumber) {
        return $query->join('reason', 'pullout.reason_id', '=', 'reason.bea_so_reason')
            ->leftJoin('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
            ->where('st_document_number', $stNumber)
            ->select('pullout.*','reason.pullout_reason','reason.id as reason_id','transport_types.transport_type');
    }

    public function scopeGetItems($query, $stNumber) {
        return $query->where('st_document_number',$stNumber)
            ->select('id','item_code','quantity');
    }

    public function scopeGetItemQty($query, $stNumber) {
        return $query->where('st_document_number', $stNumber)
			->sum('quantity');
    }

    public function scopeGetByRef($query, $stNumber) {
        return $query->where('st_document_number', $stNumber);
    }

}

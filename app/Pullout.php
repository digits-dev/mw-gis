<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pullout extends Model
{
    protected $table = 'pullout';
    protected $guarded = [];

    public function scopeGetDetailWithMoReason($query, $stNumber) {
        return $query->join('reason', 'pullout.reason_id', '=', 'reason.bea_mo_reason')
            ->leftJoin('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
            ->where('st_document_number', $stNumber)
            ->select('pullout.*','reason.pullout_reason','reason.id as reason_id','reason.bea_mo_reason as reason_mo','reason.bea_so_reason as reason_so','transport_types.transport_type');
    }

    public function scopeGetDetailWithSoReason($query, $stNumber) {
        return $query->join('reason', 'pullout.reason_id', '=', 'reason.bea_so_reason')
            ->leftJoin('transport_types', 'pullout.transport_types_id', '=', 'transport_types.id')
            ->where('st_document_number', $stNumber)
            ->select('pullout.*','reason.pullout_reason','reason.id as reason_id','reason.bea_mo_reason as reason_mo','reason.bea_so_reason as reason_so','transport_types.transport_type');
    }

    public function scopeGetItems($query, $stNumber) {
        return $query->where('st_document_number',$stNumber)
            ->select('id','item_code','quantity');
    }

    public function scopeGetItemsForApproval($query, $stNumber) {
        return $query->where('st_document_number',$stNumber)
            ->select('id','item_code','quantity','problems','problem_detail','item_description','request_type');
    }

    public function scopeGetItemQty($query, $stNumber) {
        return $query->where('st_document_number', $stNumber)
			->sum('quantity');
    }

    public function scopeGetByRef($query, $stNumber) {
        return $query->where('st_document_number', $stNumber);
    }

    public function item() : BelongsTo {
        return $this->belongsTo(Item::class, 'item_code', 'digits_code');
    }

    public function itemUpc() : BelongsTo {
        return $this->belongsTo(Item::class, 'item_code', 'upc_code');
    }
    

    public function serial() : HasMany {
        return $this->hasMany(Serials::class, 'id', 'pullout_id');
    }

}

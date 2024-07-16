<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OnhandQty extends Model
{
    protected $connection = 'oracle';
    protected $table = 'MTL_ONHAND_TOTAL_MWB_V';

    public function scopeGetOnhand($query, $subInventory = 'RETAIL'){
        return $query->join('MTL_SYSTEM_ITEMS_B','MTL_ONHAND_TOTAL_MWB_V.INVENTORY_ITEM_ID','=','MTL_SYSTEM_ITEMS_B.INVENTORY_ITEM_ID')
        ->where('MTL_SYSTEM_ITEMS_B.ORGANIZATION_ID',223)
        ->where('MTL_ONHAND_TOTAL_MWB_V.ORGANIZATION_ID',223)
        ->where('MTL_ONHAND_TOTAL_MWB_V.SUBINVENTORY_CODE',$subInventory)
        ->select(
            'MTL_SYSTEM_ITEMS_B.SEGMENT1 as ITEM_CODE',
            'MTL_ONHAND_TOTAL_MWB_V.SUBINVENTORY_CODE',
            DB::raw("SUM (MTL_ONHAND_TOTAL_MWB_V.ON_HAND) as QUANTITY")
        )
        ->groupBy(
            'MTL_SYSTEM_ITEMS_B.SEGMENT1',
            'MTL_ONHAND_TOTAL_MWB_V.SUBINVENTORY_CODE'
        );
    }
}

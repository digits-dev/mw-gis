<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReservationQtyToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('reserve_qty',false,true)->length(10)->default(0)->after('has_serial');
            $table->integer('lazada_reserve_qty',false,true)->length(10)->default(0)->after('reserve_qty');
            $table->integer('shopee_reserve_qty',false,true)->length(10)->default(0)->after('lazada_reserve_qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('reserve_qty');
            $table->dropColumn('lazada_reserve_qty');
            $table->dropColumn('shopee_reserve_qty');
        });
    }
}

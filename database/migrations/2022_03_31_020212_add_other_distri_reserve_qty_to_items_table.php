<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOtherDistriReserveQtyToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('abenson_reserve_qty')->unsigned()->default(0)->after('distri_reserve_qty');
            $table->integer('rox_reserve_qty')->unsigned()->default(0)->after('abenson_reserve_qty');
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
            $table->dropColumn('abenson_reserve_qty');
            $table->dropColumn('rox_reserve_qty');
        });
    }
}

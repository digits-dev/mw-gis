<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalDistriSubinventoryToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('pure_move_reserve_qty')->unsigned()->default(0)->after('rox_reserve_qty');
            $table->integer('sm_dept_reserve_qty')->unsigned()->default(0)->after('pure_move_reserve_qty');
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
            $table->dropColumn('pure_move_reserve_qty');
            $table->dropColumn('sm_dept_reserve_qty');
        });
    }
}

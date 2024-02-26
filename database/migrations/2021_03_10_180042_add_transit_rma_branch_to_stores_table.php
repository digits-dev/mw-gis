<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransitRmaBranchToStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('pos_warehouse_transit_branch')->nullable()->after('pos_warehouse_transit');
            $table->string('pos_warehouse_rma_branch')->nullable()->after('pos_warehouse_rma');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('pos_warehouse_transit_branch');
            $table->dropColumn('pos_warehouse_rma_branch');
        });
    }
}

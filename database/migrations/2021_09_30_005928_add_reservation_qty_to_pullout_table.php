<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReservationQtyToPulloutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pullout', function (Blueprint $table) {
            $table->date('picklist_date')->nullable()->after('pullout_date');
            $table->date('pickconfirm_date')->nullable()->after('picklist_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pullout', function (Blueprint $table) {
            $table->dropColumn('picklist_date');
            $table->dropColumn('pickconfirm_date');
        });
    }
}

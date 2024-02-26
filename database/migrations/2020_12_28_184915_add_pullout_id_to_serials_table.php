<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPulloutIdToSerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('serials', function (Blueprint $table) {
            $table->integer('pullout_id',false,true)->length(10)->nullable()->after('pos_pull_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('serials', function (Blueprint $table) {
            $table->dropColumn('pullout_id');
        });
    }
}

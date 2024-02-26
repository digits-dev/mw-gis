<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStepColumnToPulloutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pullout', function (Blueprint $table) {
            $table->integer('step',false,true)->length(10)->nullable()->after('id');
            $table->date('pullout_date')->nullable()->after('memo');
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
            $table->dropColumn('step');
            $table->dropColumn('pullout_date');
        });
    }
}

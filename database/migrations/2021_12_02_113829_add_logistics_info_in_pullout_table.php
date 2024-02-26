<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogisticsInfoInPulloutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pullout', function (Blueprint $table) {
            $table->integer('schedule_by',false,true)->length(10)->nullable()->after('schedule_date');
            $table->dateTime('log_printed_at')->nullable()->after('schedule_by');
            $table->integer('log_printed_by',false,true)->length(10)->nullable()->after('log_printed_at');
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
            $table->dropColumn('schedule_by');
            $table->dropColumn('log_printed_at');
            $table->dropColumn('log_printed_by');
        });
    }
}

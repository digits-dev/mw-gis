<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScheduleDateColumnToEbsPullTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ebs_pull', function (Blueprint $table) {
            $table->date('schedule_date')->nullable()->after('transaction_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ebs_pull', function (Blueprint $table) {
            $table->dropColumn('schedule_date');
        });
    }
}

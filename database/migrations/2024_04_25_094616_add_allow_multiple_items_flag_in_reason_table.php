<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllowMultipleItemsFlagInReasonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reason', function (Blueprint $table) {
            $table->integer('allow_multi_items')->length(10)->unsigned()->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reason', function (Blueprint $table) {
            $table->dropColumn('allow_multi_items');
        });
    }
}

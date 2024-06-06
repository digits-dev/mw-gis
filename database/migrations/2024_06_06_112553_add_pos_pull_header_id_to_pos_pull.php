<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPosPullHeaderIdToPosPull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pos_pull', function (Blueprint $table) {
            $table->integer('pos_pull_header_id')->nullable()->after('step');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pos_pull', function (Blueprint $table) {
            $table->dropColumn('pos_pull_header_id');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChannelIdColumnToPulloutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pullout', function (Blueprint $table) {
            $table->integer('channel_id',false,true)->length(10)->nullable()->after('stores_id');
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
            $table->dropColumn('channel_id');
        });
    }
}

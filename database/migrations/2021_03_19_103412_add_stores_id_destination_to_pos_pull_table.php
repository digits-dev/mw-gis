<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStoresIdDestinationToPosPullTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pos_pull', function (Blueprint $table) {
            $table->integer('stores_id_destination',false,true)->length(10)->nullable()->after('stores_id');
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
            $table->dropColumn('stores_id_destination');
        });
    }
}

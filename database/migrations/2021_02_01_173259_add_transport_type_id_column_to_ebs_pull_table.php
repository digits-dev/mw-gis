<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransportTypeIdColumnToEbsPullTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ebs_pull', function (Blueprint $table) {
            $table->integer('transport_types_id',false,true)->length(10)->nullable()->after('transaction_type');
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
            $table->dropColumn('transport_types_id');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocumentNumberColumnToPosPullTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pos_pull', function (Blueprint $table) {
            $table->integer('scheduled_by',false,true)->length(10)->nullable()->after('st_status_id');
            $table->dateTime('scheduled_at')->nullable()->after('scheduled_by');
            $table->integer('received_by',false,true)->length(10)->nullable()->after('scheduled_at');
            $table->dateTime('received_at')->nullable()->after('received_by');
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
            $table->dropColumn('scheduled_by');
            $table->dropColumn('scheduled_at');
            $table->dropColumn('received_by');
            $table->dropColumn('received_at');
        });
    }
}

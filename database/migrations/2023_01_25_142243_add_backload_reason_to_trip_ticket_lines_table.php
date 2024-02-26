<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBackloadReasonToTripTicketLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trip_ticket_lines', function (Blueprint $table) {
            $table->integer('backload_reasons_id',false,true)->length(10)->nullable()->after('is_backload');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trip_ticket_lines', function (Blueprint $table) {
            $table->dropColumn('backload_reasons_id');
        });
    }
}

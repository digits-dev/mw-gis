<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOtherDetailsToTripTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trip_tickets', function (Blueprint $table) {
            $table->string('trip_type',50)->nullable()->after('trip_number');
            $table->dateTime('received_at')->nullable()->after('is_received');
            $table->dateTime('backload_at')->nullable()->after('is_backload');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trip_tickets', function (Blueprint $table) {
            $table->dropColumn('trip_type');
            $table->dropColumn('received_at');
            $table->dropColumn('backload_at');
        });
    }
}

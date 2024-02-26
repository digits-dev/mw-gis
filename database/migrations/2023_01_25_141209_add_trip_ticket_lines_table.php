<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTripTicketLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_ticket_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trip_tickets_id',false,true)->length(10)->nullable();
            $table->string('ref_type',50)->nullable();
            $table->string('ref_number',50)->nullable();
            $table->integer('trip_qty',false,true)->length(10)->nullable();
            $table->integer('plastic_qty',false,true)->length(10)->nullable();
            $table->integer('box_qty',false,true)->length(10)->nullable();
            $table->tinyInteger('is_backload',false,true)->length(3)->default(0);
            $table->dateTime('backload_at')->nullable();
            $table->tinyInteger('is_received',false,true)->length(3)->default(0);
            $table->dateTime('received_at')->nullable();
            $table->tinyInteger('is_released',false,true)->length(3)->default(0);
            $table->dateTime('released_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_ticket_lines');
    }
}

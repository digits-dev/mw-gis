<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trip_number',50)->nullable();
            $table->integer('stores_id',false,true)->length(10)->nullable();
            $table->string('ref_number',50)->nullable();
            $table->integer('plastic_qty',false,true)->length(10)->nullable();
            $table->integer('box_qty',false,true)->length(10)->nullable();
            $table->tinyInteger('is_backload',false,true)->length(3)->default(0);
            $table->tinyInteger('is_received',false,true)->length(3)->default(0);
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
        Schema::dropIfExists('trip_tickets');
    }
}

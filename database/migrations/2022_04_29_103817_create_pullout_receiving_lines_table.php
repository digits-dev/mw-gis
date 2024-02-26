<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePulloutReceivingLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pullout_receiving_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pullout_receivings_id',false,true)->length(10)->nullable();
            $table->string('item_code',20)->nullable();
            $table->string('item_description')->nullable();
            $table->integer('quantity')->unsigned()->nullable();
            $table->integer('received_quantity')->unsigned()->nullable();
            $table->longText('serial')->nullable();
            $table->integer('has_serial',false,true)->length(10)->nullable();
            $table->longText('received_serial')->nullable();
            $table->integer('cancel_quantity')->unsigned()->nullable();
            $table->longText('cancel_reasons')->nullable();
            $table->longText('cancel_reason_details')->nullable();
            $table->longText('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pullout_receiving_lines');
    }
}

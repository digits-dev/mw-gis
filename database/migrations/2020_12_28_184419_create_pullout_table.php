<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePulloutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pullout', function (Blueprint $table) {
            $table->increments('id');
            $table->date('st_date')->nullable();
            $table->string('st_number')->nullable();
            $table->string('dr_number')->nullable();
            $table->string('st_document_number',25)->nullable();
            $table->string('si_document_number',25)->nullable();
            $table->string('memo')->nullable();
            $table->string('wh_from')->nullable();
            $table->string('wh_to')->nullable();
            $table->string('item_code',20)->nullable();
            $table->string('item_description')->nullable();
            $table->string('quantity')->nullable();
            $table->string('customer_name')->nullable();
            $table->integer('has_serial',false,true)->length(10)->nullable();
            $table->longText('serial')->nullable();
            $table->integer('st_status_id',false,true)->length(10)->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('pullout');
    }
}

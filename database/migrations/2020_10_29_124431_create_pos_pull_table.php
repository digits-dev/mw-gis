<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosPullTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_pull', function (Blueprint $table) {
            $table->increments('id');
            $table->date('received_st_date')->nullable();
            $table->string('received_st_number')->nullable();
            $table->string('memo')->nullable();
            $table->string('wh_from')->nullable();
            $table->string('wh_to')->nullable();
            $table->string('item_code',20)->nullable();
            $table->string('item_description')->nullable();
            $table->string('quantity')->nullable();
            $table->longText('serial')->nullable();
            $table->integer('st_status_id',false,true)->length(10)->nullable();

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
        Schema::dropIfExists('pos_pull');
    }
}

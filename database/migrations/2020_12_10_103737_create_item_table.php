<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('digits_code')->nullable();
            $table->string('upc_code')->nullable();
            $table->string('upc_code2')->nullable();
            $table->string('upc_code3')->nullable();
            $table->string('upc_code4')->nullable();
            $table->string('upc_code5')->nullable();
            $table->string('item_description')->nullable();
            $table->string('brand')->nullable();
            $table->integer('has_serial',false,true)->length(10)->default(0);
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('items');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbsPullTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebs_pull', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_number')->nullable();
            $table->integer('line_number',false,true)->length(10)->nullable();
            $table->integer('ordered_item',false,true)->length(10)->nullable();
            $table->integer('ordered_quantity',false,true)->length(10)->nullable();
            $table->integer('shipped_quantity',false,true)->length(10)->nullable();
            $table->string('customer_name')->nullable();
            $table->string('dr_number')->nullable();
            $table->string('transaction_type',5)->nullable();
            $table->dateTime('ship_confirmed_date')->nullable();
            $table->string('serial1')->nullable();
            $table->string('serial2')->nullable();
            $table->string('serial3')->nullable();
            $table->string('serial4')->nullable();
            $table->string('serial5')->nullable();
            $table->string('serial6')->nullable();
            $table->string('serial7')->nullable();
            $table->string('serial8')->nullable();
            $table->string('serial9')->nullable();
            $table->string('serial10')->nullable();
            $table->string('serial11')->nullable();
            $table->string('serial12')->nullable();
            $table->string('serial13')->nullable();
            $table->string('serial14')->nullable();
            $table->string('serial15')->nullable();
            $table->string('status', 20)->nullable();
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
        Schema::dropIfExists('ebs_pull');
    }
}

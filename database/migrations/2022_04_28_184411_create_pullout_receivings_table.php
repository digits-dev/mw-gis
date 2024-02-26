<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePulloutReceivingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pullout_receivings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('wrs_number',50)->nullable();
            $table->string('ref_number',50)->nullable();
            $table->string('sor_mor_number',50)->nullable();
            $table->integer('stores_id',false,true)->length(10)->nullable();
            $table->integer('reason_id',false,true)->length(10)->nullable();
            $table->string('pullout_from',150)->nullable();
            $table->string('status','50')->nullable();
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
        Schema::dropIfExists('pullout_receivings');
    }
}

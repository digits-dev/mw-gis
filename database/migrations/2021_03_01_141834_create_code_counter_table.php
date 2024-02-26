<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodeCounterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code_counter', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pullout_refcode',false,true)->length(10)->default(0);
            $table->integer('sts_refcode',false,true)->length(10)->default(0);
            $table->integer('dr_refcode',false,true)->length(10)->default(0);
            $table->enum('status',['ACTIVE','INACTIVE'])->default('ACTIVE')->nullable();
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
        Schema::dropIfExists('code_counter');
    }
}

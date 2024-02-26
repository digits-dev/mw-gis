<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePulloutReceivingSerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pullout_receiving_serials', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pullout_receiving_lines_id',false,true)->length(10)->nullable();
            $table->string('serial_number')->nullable();
            $table->enum('status',['MISMATCHED','RECEIVED'])->default('RECEIVED')->nullable();
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
        Schema::dropIfExists('pullout_receiving_serials');
    }
}

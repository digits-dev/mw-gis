<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGisPullsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gis_pulls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ref_number')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('quantity_total')->nullable();
            $table->string('memo')->nullable();
            $table->integer('stores_id')->nullable();
            $table->integer('location_id_from')->nullable();
            $table->integer('sub_location_id_from')->nullable();
            $table->string('location_from')->nullable();
            $table->integer('stores_id_destination')->nullable();
            $table->integer('location_id_to')->nullable();
            $table->integer('sub_location_id_to')->nullable();
            $table->string('location_to')->nullable();
            $table->integer('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->string('approver_comments')->nullable();
            $table->integer('received_by')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->integer('rejected_by')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->dateTime('transfer_date')->nullable();
            $table->string('hand_carrier')->nullable();
            $table->integer('transport_types_id')->nullable();
            $table->integer('reason_id')->nullable();
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
        Schema::dropIfExists('gis_pulls');
    }
}

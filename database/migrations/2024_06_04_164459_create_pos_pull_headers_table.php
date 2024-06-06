<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosPullHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_pull_headers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_number')->nullable();
            $table->string('status',50)->nullable();
            $table->dateTime('received_st_date')->nullable();
            $table->string('received_st_number',50)->nullable();
            $table->string('st_document_number',50)->nullable();
            $table->string('memo',50)->nullable();
            $table->dateTime('transfer_date')->nullable();
            $table->string('hand_carrier',50)->nullable();
            $table->integer('transport_types_id')->nullable();
            $table->integer('reason_id')->nullable();
            $table->string('wh_from',50)->nullable();
            $table->string('wh_to',50)->nullable();
            $table->integer('channel_id')->nullable();
            $table->integer('stores_id')->nullable();
            $table->integer('location_id_from')->nullable();
            $table->integer('sub_location_id_from')->nullable();
            $table->integer('stores_id_destination')->nullable();
            $table->string('location_id_to')->nullable();
            $table->string('sub_location_id_to')->nullable();
            $table->integer('st_status_id')->nullable();
            $table->longtext('file_name')->nullable();
            $table->integer('scheduled_by')->nullable();
            $table->dateTime('scheduled_at')->nullable();
            $table->integer('received_by')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->integer('confirmed_by')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->integer('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->integer('log_printed_by')->nullable();
            $table->dateTime('log_printed_at')->nullable();
            $table->integer('rejected_by')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->string('approver_comments')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->date('created_date')->nullable();
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
        Schema::dropIfExists('pos_pull_headers');
    }
}

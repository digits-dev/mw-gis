<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusWorkflowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_workflows', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_type')->nullable();
            $table->integer('channel_id',false,true)->length(10)->nullable();
            $table->string('customer_type')->nullable();
            $table->integer('transport_types_id',false,true)->length(10)->nullable();
            $table->string('workflow_status')->nullable();
            $table->integer('current_step',false,true)->length(10)->nullable();
            $table->integer('next_step',false,true)->length(10)->nullable();
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
        Schema::dropIfExists('status_workflows');
    }
}

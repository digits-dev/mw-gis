<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReviewColumnToPulloutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pullout', function (Blueprint $table) {
            $table->integer('rejected_by',false,true)->length(10)->nullable()->after('status');
            $table->dateTime('rejected_at')->nullable()->after('rejected_by');
            $table->integer('approved_by',false,true)->length(10)->nullable()->after('rejected_at');
            $table->dateTime('approved_at')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pullout', function (Blueprint $table) {
            $table->dropColumn('rejected_by');
            $table->dropColumn('rejected_at');
            $table->dropColumn('approved_by');
            $table->dropColumn('approved_at');
        });
    }
}

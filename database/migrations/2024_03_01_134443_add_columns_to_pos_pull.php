<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPosPull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pos_pull', function (Blueprint $table) {
            $table->integer('location_id_from')->length(11)->unsigned()->nullable()->after('stores_id');
            $table->integer('sub_location_id_from')->length(11)->unsigned()->nullable()->after('location_id_from');
            $table->integer('location_id_to')->length(11)->unsigned()->nullable()->after('stores_id_destination');
            $table->integer('sub_location_id_to')->length(11)->unsigned()->nullable()->after('location_id_to');
            $table->string('approver_comments')->nullable()->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pos_pull', function (Blueprint $table) {
            $table->dropColumn('location_id_from');
            $table->dropColumn('sub_location_id_from');
            $table->dropColumn('location_id_to');
            $table->dropColumn('sub_location_id_to');
            $table->dropColumn('approver_comments');
        });
    }
}

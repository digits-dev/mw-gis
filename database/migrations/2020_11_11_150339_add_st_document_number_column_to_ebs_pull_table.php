<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStDocumentNumberColumnToEbsPullTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ebs_pull', function (Blueprint $table) {
            $table->integer('st_document_number',false,true)->length(10)->nullable()->after('dr_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ebs_pull', function (Blueprint $table) {
            $table->dropColumn('st_document_number');
        });
    }
}

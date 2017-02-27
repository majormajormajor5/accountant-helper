<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMonthsTableAddColumnBuildingId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('months', function ($table) {
            $table->unsignedBigInteger('building_id');
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('months', function($table) {
            $table->dropForeign('months_building_id_foreign');
            $table->dropColumn('building_id');
        });
    }
}

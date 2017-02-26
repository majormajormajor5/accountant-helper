<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterApartmentOwnerTableAddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apartment_owner', function ($table) {
            $table->foreign('owner_id')->references('id')->on('owners')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('apartment_id')->references('id')->on('apartments')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apartment_owner', function ($table) {
            $table->dropForeign('apartment_owner_owner_id_foreign');
            $table->dropForeign('apartment_owner_apartment_id_foreign');
        });
    }
}

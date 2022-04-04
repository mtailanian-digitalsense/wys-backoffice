<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateBuildingsTable
 */
class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('address_number');
            $table->integer('building_year');
            $table->string('category');
            $table->string('gps_location');
            $table->integer('infrastructure_lvl');
            $table->string('name');
            $table->integer('parking_lvl');
            $table->integer('parking_number');
            $table->integer('public_transport_lvl');
            $table->integer('security_lvl');
            $table->integer('services_lvl');
            $table->string('street');
            $table->integer('sustainability_lvl');
            $table->integer('total_floors');
            $table->integer('view_lvl');
            $table->unsignedBigInteger('zone_id')->nullable()->unsigned();
            $table->foreign('zone_id')->references('id')->on('subcategories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {
            //
        });
    }
}

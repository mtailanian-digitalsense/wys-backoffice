<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function ($table) {
            $table->string('address_number')->nullable()->change();;
            $table->integer('building_year')->nullable()->change();;
            $table->string('category')->nullable()->change();;
            $table->string('gps_location')->nullable()->change();;
            $table->integer('infrastructure_lvl')->nullable()->change();;
            $table->string('name')->nullable()->change();;
            $table->integer('parking_lvl')->nullable()->change();;
            $table->integer('parking_number')->nullable()->change();;
            $table->integer('public_transport_lvl')->nullable()->change();;
            $table->integer('security_lvl')->nullable()->change();;
            $table->integer('services_lvl')->nullable()->change();;
            $table->string('street')->nullable()->change();;
            $table->integer('sustainability_lvl')->nullable()->change();;
            $table->integer('total_floors')->nullable()->change();;
            $table->integer('view_lvl')->nullable()->change();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

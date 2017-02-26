<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station_zone', function (Blueprint $table) {
            $table->integer('station_id');
            $table->integer('zone_id');

            $table->primary(['station_id', 'zone_id']);

            $table->foreign('station_id')->references('id')->on('stations');
            $table->foreign('zone_id')->references('id')->on('zones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('station_zone');
    }
}

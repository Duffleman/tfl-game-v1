<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLineStationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_station', function (Blueprint $table) {
            $table->integer('line_id');
            $table->integer('station_id');

            $table->primary(['line_id', 'station_id']);

            $table->foreign('station_id')->references('id')->on('stations');
            $table->foreign('line_id')->references('id')->on('lines');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('line_station');
    }
}

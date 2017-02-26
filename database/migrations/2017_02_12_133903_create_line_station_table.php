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
        $stmt = 'CREATE TABLE line_station (
            line_id INTEGER NOT NULL,
            station_id INTEGER NOT NULL,

            PRIMARY KEY (line_id, station_id)
        );';

        \DB::statement($stmt);
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

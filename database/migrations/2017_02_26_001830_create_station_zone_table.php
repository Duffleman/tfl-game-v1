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
        $stmt = 'CREATE TABLE station_zone (
            station_id INTEGER NOT NULL,
            zone_id INTEGER NOT NULL,

            PRIMARY KEY (station_id, zone_id)
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
        Schema::dropIfExists('station_zone');
    }
}

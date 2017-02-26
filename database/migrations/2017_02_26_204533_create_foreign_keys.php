<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('line_station', function (Blueprint $table) {
        //     $table->foreign('station_id')->references('id')->on('stations');
        //     $table->foreign('line_id')->references('id')->on('lines');
        // });

        // Schema::table('questions', function (Blueprint $table) {
        //     $table->foreign('game_state_id')->references('id')->on('game_states');
        //     $table->foreign('station_id')->references('id')->on('stations');
        // });

        // Schema::table('station_zone', function (Blueprint $table) {
        //     $table->foreign('station_id')->references('id')->on('stations');
        //     $table->foreign('zone_id')->references('id')->on('zones');
        // });

        // Schema::table('aliases', function (Blueprint $table) {
        //     $table->foreign('station_id')->references('id')->on('stations');
        // });
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

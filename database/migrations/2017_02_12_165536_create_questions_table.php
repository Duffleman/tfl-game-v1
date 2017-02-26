<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            // TODO: This could be a pivot table with additional attributes
            $table->increments('id');
            $table->integer('game_state_id');
            $table->integer('station_id');
            $table->string('question');
            $table->string('user_answer')->nullable();
            $table->datetime('answered_at')->nullable();
            $table->timestamps();

            $table->foreign('game_state_id')->references('id')->on('game_states');
            $table->foreign('station_id')->references('id')->on('stations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}

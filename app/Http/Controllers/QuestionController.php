<?php

namespace TFLGame\Http\Controllers;

use TFLGame\Http\Requests;
use TFLGame\GameState;
use TFLGame\Question;
use TFLGame\Station;
use TFLGame\Services\ScoreCalculator;
use TFLGame\Services\GameController;

class QuestionController extends Controller
{
    public function getQuestion(GameState $state)
    {
        $answered = Question::where('game_state_id', $state->id)
                    ->whereNotNull('answered_at')
                    ->get();

        if ($answered->count() >= 20) {
            throw new \Exception('game_state_finished');
        }

        $existing = Question::where('game_state_id', $state->id)
                    ->whereNull('answered_at')
                    ->first();

        if ($existing) {
            return $existing;
        }

        $station = GameController::getStation($state, $answered);
        $question = GameController::generateQuestion($station);

        return Question::create([
            'game_state_id' => $state->id,
            'station_id' => $station->id,
            'question' => $question,
        ]);
    }

    public function help(GameState $state)
    {
        $question = $state->latestQuestion();
        $station = Station::find($question->station_id);

        return [
            'zones' => $station->zones->pluck('label'),
            'lines' => $station->lines->pluck('name'),
        ];
    }

    public function answer(GameState $state, Requests\AnswerRequest $request)
    {
        $question = $state->latestQuestion();
        $answer = $question->answer($request->answer);

        return $answer;
    }
}

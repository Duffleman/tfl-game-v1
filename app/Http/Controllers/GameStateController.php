<?php

namespace TFLGame\Http\Controllers;

use Illuminate\Http\Request;
use TFLGame\Http\Requests;
use TFLGame\GameState;
use TFLGame\Services\ScoreCalculator;
use Hashids\Hashids;

class GameStateController extends Controller
{
    public function create(Requests\NewGameStateRequest $request) {
    	$state = GameState::create($request->all());
    	$hasher = new Hashids(config('app.key'));

    	$state->code = $hasher->encode($state->id);
    	$state->save();

    	return [
    		'code' => $state->code,
    		'player' => $state->player,
    	];
    }

    public function uwotm8(GameState $state) {
        $answered = $state->questions()->whereNotNull('answered_at')->orderBy('created_at')->get();

        return $answered->map(function ($question) {
            return $question;
        });
    }

    public function result(GameState $state) {
        $questions = $state->questions()->whereNull('answered_at')->orderBy('created_at')->get();
        $answered = $state->questions()->whereNotNull('answered_at')->orderBy('created_at')->get();
        $progress = 'created';

        if ($answered->count() > 0)
            $progress = 'in-progress';

        if ($answered->count() >= 20)
            $progress = 'complete';

        $score = ScoreCalculator::calculate($state, $answered);

        return [
            'player' => $state->player,
            'started_at' => $state->created_at->toDayDateTimeString(),
            'state' => $progress,
            'question_waiting' => $questions->count() === 1 ? true : false,
            'questions' => $answered->pluck('question'),
            'score' => $score,
        ];
    }
}

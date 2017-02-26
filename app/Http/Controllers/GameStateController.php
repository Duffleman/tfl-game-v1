<?php

namespace TFLGame\Http\Controllers;

use Illuminate\Http\Request;
use TFLGame\GameState;
use TFLGame\Station;
use TFLGame\Services\ScoreCalculator;
use Hashids\Hashids;

class GameStateController extends Controller
{
    public function create(Request $request)
    {
        $this->validateNewGameRequest($request);

        $state = GameState::create([
            'player' => $request->player,
            'config' => $request->config,
        ]);

        $lines = $request->config['lines'];
        $zones = $request->config['zones'];

        $answerPool = Station::whereHas('lines', function ($query) use ($lines) {
                $query->whereIn('code', $lines);
            })
            ->whereHas('zones', function ($query) use ($zones) {
                $query->whereIn('label', $zones);
            })
            ->count();

        $minQuestions = config('game.question_count');

        if ($answerPool < $minQuestions) {
            throw new \Exception('params_wont_allow_game');
        }

        $hasher = new Hashids(config('app.key'));

        $state->code = $hasher->encode($state->id);
        $state->save();

        return [
            'code' => $state->code,
            'player' => $state->player,
            'config' => $state->config,
            'pool' => $answerPool,
        ];
    }

    private function validateNewGameRequest(Request $request) {
        $rules = [];

        $this->validate($request, [
            'player' => '',
            'config' => 'required',
            'config.lines' => 'array|min:1',
            'config.zones' => 'array|min:1',
        ]);

        foreach ($request->config['lines'] as $i => $line) {
            $rules["config.lines.${i}"] = 'string|exists:lines,code';
        }

        foreach ($request->config['zones'] as $i => $line) {
            $rules["config.zones.${i}"] = 'exists:zones,label';
        }

        $this->validate($request, $rules);
    }

    public function getQuestion(GameState $state) {
        try {
            return $state->latestQuestion();
        } catch (\Exception $ex) {
            if ($ex->getMessage() !== 'no_question_assigned')
                throw $ex;

            return null;
        }
    }

    public function result(GameState $state)
    {
        $question = $this->getQuestion($state);

        $answered = $state->questions()->whereNotNull('answered_at')->orderBy('created_at')->get();
        $progress = 'created';

        $timeTaken = $answered->first()->created_at->diffForHumans($answered->last()->updated_at, true);

        if ($answered->count() > 0) {
            $progress = 'in-progress';
        }

        if ($answered->count() >= config('game.question_count')) {
            $progress = 'complete';
        }

        $stationIds = $answered->map(function ($question) {
            return $question->station_id;
        });

        $stations = Station::whereIn('id', $stationIds)->get();
        $stations = $stations->keyBy('id');

        $score = ScoreCalculator::calculate($state, $answered, $stations);

        return [
            'player' => $state->player,
            'started_at' => $state->created_at->toIso8601String(),
            'state' => $progress,
            'question_waiting' => $question ? true : false,
            'time_taken' => $timeTaken,
            'score' => $score,
        ];
    }
}

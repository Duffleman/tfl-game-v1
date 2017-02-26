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
            return $existing->show();
        }

        $question = GameController::getName($state, $answered);

        return Question::create([
            'game_state_id' => $state->id,
            'question' => $question['question'],
            'answer' => $question['answer'],
        ])->show();
    }

    public function help(GameState $state)
    {
        $existing = Question::where('game_state_id', $state->id)
                    ->whereNull('answered_at')
                    ->first();

        $station = Station::where('cleanName', $existing->answer)->first();

        return [
            'lines' => $station->lines->pluck('name'),
        ];
    }

    public function answer(GameState $state, Requests\AnswerRequest $request)
    {
        $answered = Question::where('game_state_id', $state->id)
                    ->whereNotNull('answered_at')
                    ->get();

        if ($answered->count() >= 20) {
            throw new \Exception('game_state_finished');
        }

        try {
            $question = $state->latestQuestion();
            $answer = $question->answer($request->answer);

            return $answer;
        } catch (\Throwable $error) {
            throw $error;
        }
    }

    public function uwotm8(GameState $state)
    {
        $answered = $state->questions()->whereNotNull('answered_at')->orderBy('created_at')->get();

        return $answered->map(function ($question) {
            $station = Station::where('cleanName', $question->answer)->first();

            $cleanName = $station->cleanName;
            $user_answer = $question->user_answer;
            $correct = ScoreCalculator::isCorrect($cleanName, $user_answer);

            return [
                'question' => $question->question,
                'answer' => $station->shortName,
                'user_answer' => $question->user_answer,
                'correct' => $correct,
                'name' => $station->longName,
                'lines' => $station->lines->pluck('name'),
            ];
        });
    }
}

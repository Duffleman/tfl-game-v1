<?php

namespace TFLGame\Http\Controllers;

use TFLGame\Http\Requests;
use TFLGame\GameState;
use TFLGame\Question;
use TFLGame\Services\GameController;

class QuestionController extends Controller
{
    public function getName(GameState $state)
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

        $question = GameController::getName($answered);

        return Question::create([
            'game_state_id' => $state->id,
            'question' => $question['question'],
            'answer' => $question['answer'],
        ])->show();
    }

    public function answer(GameState $state, Requests\AnswerRequest $request)
    {
        try {
            $question = $state->latestQuestion()->answer($request->answer);

            return $question;
        } catch (\Throwable $error) {
            throw new \Exception('no_question_asked');
        }
    }
}

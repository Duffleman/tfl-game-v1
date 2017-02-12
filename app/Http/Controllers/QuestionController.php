<?php

namespace TFLGame\Http\Controllers;

use Illuminate\Http\Request;
use TFLGame\GameState;
use TFLGame\Question;
use TFLGame\Services\GameController;

class QuestionController extends Controller
{
    public function getName(GameState $state) {
        $answered = Question::where('game_state_id', $state->id)
                    ->whereNotNull('answered_at')
                    ->get();

        if ($answered->count() >= 20) {
            return [
                'code' => 'game_state_over',
                'results' => [],
            ];
        }

        $existing = Question::where('game_state_id', $state->id)
                    ->whereNull('answered_at')
                    ->first();

        if ($existing)
            return $existing->show();

        $question = GameController::getName($answered);

        return Question::create([
            'game_state_id' => $state->id,
            'question' => $question['question'],
            'answer' => $question['answer'],
        ])->show();
    }
}

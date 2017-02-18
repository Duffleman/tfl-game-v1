<?php

namespace TFLGame;

use Illuminate\Database\Eloquent\Model;
use TFLGame\Services\ScoreCalculator;

class Question extends Model
{
    protected $fillable = [
        'game_state_id',
        'question',
        'answer',
    ];

    protected $hidden = [
        'id', 'game_state_id', 'answer', 'user_answer', 'answered_at', 'created_at', 'updated_at',
    ];

    public function answer($answer)
    {
        $this->user_answer = $answer;
        $this->answered_at = \Carbon\Carbon::now();

        $this->save();

        $station = Station::where('cleanName', $this->answer)->first();

        return [
            'answer' => $station->shortName,
            'user_answer' => $answer,
            'correct' => ScoreCalculator::isCorrect($station->cleanName, $answer),
            'lines' => $station->lines->pluck('name'),
            'given' => $this->question,
        ];
    }

    public function show()
    {
        if (!$this->answered_at) {
            return $this;
        }

        $this->makeVisible('user_answer');

        return $this;
    }
}

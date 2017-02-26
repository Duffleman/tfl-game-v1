<?php

namespace TFLGame;

use Illuminate\Database\Eloquent\Model;
use TFLGame\Services\ScoreCalculator;

class Question extends Model
{
    protected $fillable = [
        'game_state_id',
        'station_id',
        'question',
    ];

    protected $hidden = [
        'id', 'game_state_id', 'station_id', 'user_answer', 'answered_at', 'created_at', 'updated_at',
    ];

    public function station()
    {
        return $this->hasOne(Station::class);
    }

    public function answer($answer)
    {
        $this->user_answer = $answer;
        $this->answered_at = \Carbon\Carbon::now();

        $this->save();

        $station = Station::find($this->station_id);

        return [
            'answer' => $station->shortName,
            'user_answer' => $answer,
            'correct' => ScoreCalculator::isCorrect($station->cleanName, $answer),
            'lines' => $station->lines->pluck('name'),
            'zones' => $station->zones->pluck('label'),
            'given' => $this->question,
        ];
    }
}

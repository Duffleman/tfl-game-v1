<?php

namespace TFLGame;

use Illuminate\Database\Eloquent\Model;

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

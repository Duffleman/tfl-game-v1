<?php

namespace TFLGame;

use Illuminate\Database\Eloquent\Model;

class GameState extends Model
{
    protected $fillable = ['code', 'player', 'config'];
    protected $hidden = ['id', 'updated_at'];

    public function getConfigAttribute($value) {
        return self::decode($value);
    }

    public function setConfigAttribute($value) {
        $this->attributes['config'] = self::encode($value);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function latestQuestion()
    {
        $question = $this->questions()->whereNull('answered_at')->first();

        if (!$question) {
            throw new \Exception('no_question_assigned');
        }

        return $question;
    }

    public function getRouteKeyName()
    {
        return 'code';
    }

    public static function encode(array $body)
    {
        $json = json_encode($body, JSON_UNESCAPED_SLASHES);

        return $json;
    }

    public static function decode($body)
    {
        $array = json_decode($body, true);

        return $array;
    }
}

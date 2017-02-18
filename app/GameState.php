<?php

namespace TFLGame;

use Illuminate\Database\Eloquent\Model;

class GameState extends Model
{
    protected $fillable = ['code', 'player'];
    protected $hidden = ['id', 'updated_at'];

    public function lines()
    {
        return $this->belongsToMany(Line::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function latestQuestion()
    {
        return $this->questions()->whereNull('answered_at')->first();
    }

    public function getRouteKeyName()
    {
        return 'code';
    }
}

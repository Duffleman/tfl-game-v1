<?php

namespace TFLGame;

use Illuminate\Database\Eloquent\Model;

class GameState extends Model
{
    protected $fillable = ['code', 'player'];
    protected $hidden = ['id', 'updated_at'];

    public function questions() {
    	return $this->hasMany(Question::class);
    }

    public function getRouteKeyName() {
	    return 'code';
	}
}

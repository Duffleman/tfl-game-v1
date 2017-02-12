<?php

namespace TFLGame;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = ['longName', 'shortName', 'cleanName'];

    public function lines() {
    	return $this->belongsToMany(Line::class);
    }
}

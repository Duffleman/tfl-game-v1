<?php

namespace TFLGame;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = ['tflId', 'longName', 'shortName', 'cleanName'];

    public function lines()
    {
        return $this->belongsToMany(Line::class);
    }

    public function aliases()
    {
        return $this->hasMany(Alias::class);
    }

    public function zones()
    {
        return $this->belongsToMany(Zone::class);
    }
}

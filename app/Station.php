<?php

namespace TFLGame;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = ['tflId', 'longName', 'shortName', 'cleanName', 'zone'];

    public function lines()
    {
        return $this->belongsToMany(Line::class);
    }
}

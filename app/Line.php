<?php

namespace TFLGame;

use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    protected $fillable = [
        'code', 'name', 'type',
    ];

    public function stations()
    {
        return $this->belongsToMany(Station::class);
    }
}

<?php

namespace TFLGame;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    public $fillable = ['label'];
    public $hidden = ['created_at', 'updated_at', 'id', 'pivot'];

    public function stations()
    {
        return $this->belongsToMany(Station::class);
    }

    public function stationNames()
    {
        return $this->stations->map(function ($station) {
            return $station->shortName;
        });
    }
}

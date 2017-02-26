<?php

namespace TFLGame;

use Illuminate\Database\Eloquent\Model;

class Alias extends Model
{
    public $fillable = ['station_id', 'name'];
    public $hidden = ['id', 'station_id', 'created_at', 'updated_at'];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}

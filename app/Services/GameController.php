<?php

namespace TFLGame\Services;

use TFLGame\Station;
use TFLGame\GameState;
use Illuminate\Support\Collection;

class GameController
{
    public static function getStation(GameState $state, Collection $stationsGiven)
    {
        $alreadyGiven = $stationsGiven->map(function ($station) {
            return $station->id;
        });

        $config = $state->config;

        $lines = $config['lines'];
        $zones = $config['zones'];

        $station = Station::whereNotIn('id', $alreadyGiven)
            ->whereHas('lines', function ($query) use ($lines) {
                $query->whereIn('code', $lines);
            })
            ->whereHas('zones', function ($query) use ($zones) {
                $query->whereIn('label', $zones);
            })
            ->get()
            ->random();

        if ($station->count() === 0) {
            throw new \Exception('no_stations_match_params');
        }

        return $station;
    }

    public static function generateQuestion(Station $station) {
        $space = ' ';
        $text = preg_replace("/[AEIOU]/", "", $station->cleanName);
        $text = str_replace("_", "", $text);

        $spaceCount = spaceCount(strlen($text));
        $array = str_split($text);
        $used = [];

        for ($c = $spaceCount; $c > 0; $c--) {
            $r = getPosition($array, $used);

            array_splice($array, $r, 0, $space);
        }

        $text = trim(implode($array, ''));
        $text = preg_replace("/\s\s+/", " ", $text);

        return $text;
    }
}

function getPosition($array, &$used = [])
{
    $position = rand(1, count($array) - 1);

    if ($array[$position] === ' ') {
        return getPosition($array, $used);
    } else {
        $used[] = $position;
    }

    return $position;
}

function spaceCount($wordLength)
{
    $max = 4;
    $count = round($wordLength / 2, 0, PHP_ROUND_HALF_DOWN);

    return min($max, $count);
}

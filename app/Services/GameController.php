<?php

namespace TFLGame\Services;

use TFLGame\Station;
use TFLGame\GameState;

class GameController
{
    public static function getName(GameState $state, $answered)
    {
        $alreadyGiven = $answered->map(function ($ans) {
            return $ans->answer;
        });

        $lines = $state->lines()->get()->pluck('id')->toArray();

        $station = Station::whereNotIn('cleanName', $alreadyGiven)
            ->whereHas('lines', function ($query) use ($lines) {
                $query->whereIn('line_id', $lines);
            })
            ->get()
            ->random();

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

        return [
            'question' => $text,
            'answer' => $station->cleanName,
        ];
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

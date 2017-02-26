<?php

namespace TFLGame\Services;

use Duffleman\JSONClient\JSONClient;
use Duffleman\JSONClient\Collections\Generic;

class TFLAPI
{

    private static $removals = [
        'International',
        'London',
        'Rail Station',
        'Underground Station',
        'DLR Station',
        '-Underground', // Weird one for Paddington
    ];

    public function __construct($app_id, $app_key)
    {
        $this->app_id = $app_id;
        $this->app_key = $app_key;
        $this->client = new JSONClient('https://api.tfl.gov.uk/');
    }

    public function listLines()
    {
        $lines = [];

        $resultSet = $this->client->get('line/mode/tube,overground,dlr,tflrail/status', [
            'app_id' => $this->app_id,
            'app_key' => $this->app_key,
        ]);

        foreach ($resultSet as $line) {
            $lines[] = new Generic([
                'id' => $line->id,
                'name' => $line->name,
                'mode' => $line->modeName,
            ]);
        }

        return collect($lines);
    }

    public function listStations($line)
    {
        $stations = [];

        $resultSet = $this->client->get("line/{$line}/stoppoints", [
            'app_id' => $this->app_id,
            'app_key' => $this->app_key,
        ]);

        foreach ($resultSet as $station) {
            $shortName = $station->commonName;

            foreach (self::$removals as $term) {
                $shortName = str_replace($term, '', $shortName);
            }

            // One small exception
            if (trim($shortName) === 'Bridge') {
                $shortName = 'London Bridge';
            }

            $shortName = trim($shortName);
            $shortName = preg_replace("/\s\(.*\)/", "", $shortName);

            $cleanName = strtoupper(preg_replace("/[^\w\s]/", "", $shortName));
            $cleanName = str_replace(' ', '_', $cleanName);

            $additionalProperties = $station->additionalProperties;

            $zone = null;
            $zoneProperty = $additionalProperties->where('key', 'Zone');

            if ($zoneProperty->first()) {
                $zone = $zoneProperty->first()['value'];
            }

            // Weird Overrides
            if ($station->id === '910GBUSHYDC') { // Bushey -> 8
                $zone = '8';
            }

            if ($station->id === '910GCLPHMJ1') { // Clapham Junction -> 2
                $zone = '2';
            }

            $zones = explode('+', $zone);

            $stations[] = new Generic([
                'id' => $station->id,
                'line' => $line,
                'longName' => $station->name ?? $station->commonName,
                'shortName' => $shortName,
                'cleanName' => $cleanName,
                'zones' => $zones,
                'type' => $station->placeType,
                'status' => $station->status,
                'location' => [
                    'latitude' => $station->lat,
                    'longitude' => $station->lon,
                ],
            ]);
        }

        return collect($stations);
    }
}

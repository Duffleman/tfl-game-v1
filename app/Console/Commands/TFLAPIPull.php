<?php

namespace TFLGame\Console\Commands;

use Illuminate\Console\Command;
use TFLGame\Services\TFLAPI;
use TFLGame\Alias;
use TFLGame\Line;
use TFLGame\Station;
use TFLGame\Zone;

class TFLAPIPull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tfl:pull';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the internal DB of lines and stations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $app_id = config('services.tfl.app_id');
        $app_key = config('services.tfl.app_key');

        $this->api = new TFLAPI($app_id, $app_key);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $table = [];

        $this->clean();
        $this->info('Grabbing list of lines.');

        $lines = $this->api->listLines();

        $this->info("Downloaded {$lines->count()} lines.");

        $bar = $this->output->createProgressBar($lines->count());

        foreach ($lines as $line) {
            $stations = $this->api->listStations($line->id);

            $table[] = ['line' => $line->name, 'stations' => $stations->count()];

            // Build Station in DB
            foreach ($stations as $station) {
                $dbStation = Station::firstOrCreate([
                    'shortName' => $station->shortName,
                    'cleanName' => $station->cleanName,
                ]);

                // If long name, give alias
                if ($station->longName) {
                    $alias = Alias::firstOrCreate([
                        'name' => $station->longName,
                        'station_id' => $dbStation->id,
                    ]);

                    $alias->tflId = $station->id;
                    $alias->save();

                    $dbStation->aliases()->save($alias);
                }

                // Relate to Zones
                $zones = $station->zones;

                foreach ($zones as $zone) {
                    if ($zone) {
                        $dbZone = Zone::firstOrCreate([
                            'label' => $zone,
                        ]);

                        if (!in_array($dbStation->shortName, $dbZone->stationNames()->toArray())) {
                            $dbZone->stations()->attach($dbStation);
                        }
                    }
                }

                // Relate to Line
                $dbLine = Line::firstOrCreate([
                    'code' => $line->id,
                    'name' => $line->name,
                    'type' => $line->mode,
                ]);

                if (!in_array($dbStation->shortName, $dbLine->stations->pluck('shortName')->toArray())) {
                    $dbLine->stations()->attach($dbStation);
                }
            }

            $bar->advance();
        }

        $bar->finish();

        $headers = ['Line', 'Stations'];

        usort($table, function ($a, $b) {
            return $a['stations'] <=> $b['stations'];
        });

        $table = collect($table)->reverse();

        echo("\n\n");
        $this->table($headers, $table);
    }

    private function clean()
    {
        $this->info('Wiping existing data.');

        \DB::table('aliases')->truncate();
        \DB::table('zones')->truncate();
        \DB::table('lines')->truncate();
        \DB::table('stations')->truncate();
        \DB::table('line_station')->truncate();
    }
}

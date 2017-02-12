<?php

namespace TFLGame\Console\Commands;

use Illuminate\Console\Command;
use TFLGame\Services\TFLAPI;

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
        $this->clean();

        $stations = [];
        $this->info('Grabbing list of lines.');
        $lines = $this->api->listLines();

        foreach ($lines as $line) {
            \TFLGame\Line::create([
                'code' => $line['id'],
                'name' => $line['name'],
                'type' => $line['mode'],
            ]);

            $this->info("Grabbing station list for the ${line['id']} line.");
            $stations[$line['id']] = $this->api->listStations($line['id']);
        }

        $lines = \TFLGame\Line::all();
        $dictLines = $lines->keyBy('code');

        foreach ($stations as $line => $stations) {
            foreach ($stations as $station) {
                $db = \TFLGame\Station::firstOrCreate([
                    'cleanName' => $station['cleanName'],
                    'shortName' => $station['shortName'],
                ]);

                if (empty($db->longName)) {
                    $db->longName = $station['longName'];

                    $db->save();
                }

                $dbLine = $dictLines[$line];

                $db->lines()->attach($dbLine);
            }
        }
    }

    private function clean() {
        $this->info('Wiping existing data.');

        \DB::table('lines')->truncate();
        \DB::table('stations')->truncate();
        \DB::table('line_station')->truncate();
    }
}

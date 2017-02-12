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
        $stations = [];
        $lines = $this->api->listLines();

        foreach ($lines as $line) {
            $stations = $this->api->listStations($line['id']);

            foreach ($stations as $station) {
                var_dump($station);
            }
        }
    }
}

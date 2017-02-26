<?php

namespace TFLGame\Console\Commands;

use Illuminate\Console\Command;

class ClearGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears all game states and questions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $env = config('app.env');

        if ($env !== 'local') {
            throw new \Exception('cannot_clear_outside_of_local_env');
        }

        return $this->clean();
    }

    private function clean()
    {
        $this->info('Wiping existing data.');

        \DB::table('game_states')->truncate();
        \DB::table('questions')->truncate();

        $this->info('Games cleared.');
    }
}

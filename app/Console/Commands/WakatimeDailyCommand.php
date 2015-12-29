<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Lumen\Application;
use Mabasic\WakaTime\WakaTime;

class WakatimeDailyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wakatime:daily {project?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get daily commit stats per project';

    /**
     * @var WakaTime
     */
    protected $client;

    /**
     * Create a new command instance.

     * @return void
     */
    public function __construct(Application $app)
    {
        parent::__construct();

        $this->client = $app->make('Wakatime\API');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dd($this->client->commits('0aba0c3c-f60a-4ef5-8755-784f6fc4f5f2'));
    }
}
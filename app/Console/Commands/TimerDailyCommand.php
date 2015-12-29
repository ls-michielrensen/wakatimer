<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Lumen\Application;
use SEOshop\Service\Contracts\TimerServiceInterface;

class TimerDailyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timer:daily {project?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get daily timer stats per project';

    /**
     * @var TimerServiceInterface
     */
    protected $service;

    /**
     * Create a new command instance.

     * @return void
     */
    public function __construct(Application $app, TimerServiceInterface $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dd($this->service->handle($this->argument('project')));
    }
}
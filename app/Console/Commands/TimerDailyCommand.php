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
    protected $signature = 'timer:daily
                            {date? : The date of the entries to be parsed}
                            {project? : The project to be parsed}
                            {--e|export : Whether the results should be exported to Toggl}';

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
        $results = $this->service->handle($this->argument('date'), $this->argument('project'));

        $parsed = $this->parseResults($results);

        $export = $this->option('export');

        if ($export === true)
        {
            $this->service->exportResults($results);
        }

        // Display results
        $this->table($parsed['headers'], $parsed['rows']);
    }

    protected function parseResults($results)
    {
        $headers = ['description', 'tickets', 'time'];

        $rows = [];

        foreach($results as $result)
        {
            $rows[] = [
                'description' => '- ' . $result['commit']['message'],
                'ticket' => $result['tickets'],
                'time' => $result['commit']['total_seconds'],
            ];
        }

        return [
            'headers' => $headers,
            'rows' => $rows
        ];
    }
}
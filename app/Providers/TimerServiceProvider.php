<?php

namespace App\Providers;

use App\Console\Commands\TimerDailyCommand;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;
use SEOshop\Service\Contracts\JiraServiceInterface;
use SEOshop\Service\Contracts\TimerServiceInterface;
use SEOshop\Service\TimerService;

class TimerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TimerServiceInterface::class, TimerService::class);

        $this->app->singleton('console.timer.daily', function(Application $app) {
            return new TimerDailyCommand(
                $app,
                $app->make(TimerServiceInterface::class),
                $app->make(JiraServiceInterface::class)
            );
        });

        $this->commands([
            'console.timer.daily'
        ]);
    }
}
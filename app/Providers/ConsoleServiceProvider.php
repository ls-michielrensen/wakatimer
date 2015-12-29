<?php

namespace App\Providers;

use App\Console\Commands\WakatimeDailyCommand;
use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('console.wakatime.daily', function($app) {
            return new WakatimeDailyCommand($app);
        });

        $this->commands([
            'console.wakatime.daily'
        ]);
    }
}
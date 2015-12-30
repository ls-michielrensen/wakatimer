<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application;
use Mabasic\WakaTime\WakaTime;
use GuzzleHttp\Client as Guzzle;
use SEOshop\Service\Contracts\WakatimeServiceInterface;
use SEOshop\Service\WakatimeService;

class WakatimeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('api.wakatime', function() {
            $apiKey = env('WAKATIME_API_KEY');

            return new WakaTime(new Guzzle(), $apiKey);
        });

        $this->app->bind(WakatimeServiceInterface::class, function(Application $app) {
            return new WakatimeService($app->make('api.wakatime'));
        });
    }
}
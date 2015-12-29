<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Jira_Api_Authentication_Basic;
use Mabasic\WakaTime\WakaTime;
use GuzzleHttp\Client as Guzzle;
use Jira_Api;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Wakatime\API', function($app) {
            $apiKey = env('WAKATIME_API_KEY');

            return new WakaTime(new Guzzle(), $apiKey);
        });

        $this->app->bind('JIRA\API', function($app) {
            $username = env('JIRA_USERNAME');
            $password = env('JIRA_PASSWORD');
            $url = env('JIRA_PASSWORD');

            return new Jira_Api($url, new Jira_Api_Authentication_Basic($username, $password));
        });
    }
}
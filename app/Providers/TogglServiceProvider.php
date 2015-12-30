<?php
namespace App\Providers;

use AJT\Toggl\TogglClient;
use Guzzle\Service\Description\ServiceDescription;
use Illuminate\Support\ServiceProvider;
use SEOshop\Service\Contracts\TogglServiceInterface;
use SEOshop\Service\TogglService;

class TogglServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('api.toggl', function() {
            $apiKey = env('TOGGL_API_KEY');

            $description = ServiceDescription::factory($this->app->basePath().'/vendor/ajt/guzzle-toggl/src/AJT/Toggl/services_v8.json');

            $client =  TogglClient::factory(['api_key' => $apiKey, 'debug' => true]);
            $client->setDescription($description);

            return $client;
        });

        $this->app->bind(TogglServiceInterface::class, function() {
            return new TogglService($this->app->make('api.toggl'));
        });
    }
}
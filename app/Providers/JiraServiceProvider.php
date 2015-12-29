<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SEOshop\Service\Contracts\JiraServiceInterface;
use SEOshop\Service\JiraService;

class JiraServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Jira\API', function ($app) {
            return null;
        });

        $this->app->bind(JiraServiceInterface::class, JiraService::class);
    }
}
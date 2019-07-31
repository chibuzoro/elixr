<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // all resources
        $repos = Config::get('resource.repositories');
        // enable v1 apis
        foreach ($repos['v1'] as $identifier) {
            $this->app->singleton($identifier['repo'], function ($app) use ($identifier) {
                return new $identifier['repo'](new $identifier['model']);
            });
        }

    }
}

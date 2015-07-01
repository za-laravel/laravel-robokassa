<?php

namespace ZaLaravel\LaravelRobokassa;

use Illuminate\Support\ServiceProvider;

/**
 * Class LaravelRobokassaServiceProvider
 * @package ZaLaravel\LaravelRobokassa
 */
class LaravelRobokassaServiceProvider extends ServiceProvider {

    /**
     * @return void
     */
    public function boot()
    {
        // Migrations
        $this->publishes([
            __DIR__.'/../../database/migrations/' => base_path('/database/migrations')
        ], 'migrations');

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
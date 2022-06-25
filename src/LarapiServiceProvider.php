<?php

namespace Andr3a\Larapi;

use Illuminate\Support\ServiceProvider;

class LarapiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(Larapi::class, function ($app) {
            return new Larapi();
        });
    }
}

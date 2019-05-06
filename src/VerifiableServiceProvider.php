<?php

namespace Tumainimosha\Verifiable;

use Illuminate\Support\ServiceProvider;

class VerifiableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/verifiable.php', 'verifiable');
        $this->publishThings();
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'Verifiable');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function publishThings()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/verifiable.php' => config_path('verifiable.php'),
            ], 'config');
        }
    }
}

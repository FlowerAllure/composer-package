<?php

namespace FlowerAllure\ComposerUtils\App\Provider;

use FlowerAllure\ComposerUtils\App\Services\WeatherServer;

class WeatherProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(WeatherServer::class, function () {
            return new WeatherServer(config('services.weather.key'));
        });

        // $this->app->alias(WeatherServer::class, 'weather');
    }

    public function provides(): array
    {
        return [WeatherServer::class];
    }
}

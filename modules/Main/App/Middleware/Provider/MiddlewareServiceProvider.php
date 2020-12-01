<?php


namespace Main\App\Middleware\Provider;

use Illuminate\Support\ServiceProvider;
use Main\App\Middleware\CustomAuth;
use Main\App\Middleware\Localization;

class MiddlewareServiceProvider extends ServiceProvider
{
    public function register()
    {
        app('router')->aliasMiddleware('Localization', Localization::class);
        app('router')->aliasMiddleware('custom_auth', CustomAuth::class);
    }

    public function boot()
    {

    }
}

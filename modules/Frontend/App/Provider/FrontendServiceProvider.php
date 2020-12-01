<?php


namespace Frontend\App\Provider;

use Illuminate\Support\ServiceProvider;

class FrontendServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../Routes/frontend_routes.php');
        $this->loadTranslationsFrom(__DIR__ . '/../../Resources/lang', 'FrontendLang');
        $this->loadViewsFrom(__DIR__ . '/../../Resources/views', 'Frontend');
    }

    public function boot()
    {

    }
}

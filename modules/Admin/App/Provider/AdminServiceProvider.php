<?php


namespace Admin\App\Provider;

use App;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../Routes/admin_routes.php');
        $this->loadTranslationsFrom(__DIR__ . '/../../Resources/lang', 'AdminLang');
        $this->loadViewsFrom(__DIR__ . '/../../Resources/views', 'Admin');
    }

    public function boot()
    {

    }
}

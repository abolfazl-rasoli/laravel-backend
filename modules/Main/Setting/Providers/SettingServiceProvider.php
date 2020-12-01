<?php


namespace Main\Setting\Providers;


use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Main\Role\Model\Role;

class SettingServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/setting_routes.php');
    }
}

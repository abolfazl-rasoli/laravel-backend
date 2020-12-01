<?php


namespace Main\Role\Providers;


use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Main\Role\Model\Role;

class RoleServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/role_routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        Route::model('role', Role::class);
    }
}

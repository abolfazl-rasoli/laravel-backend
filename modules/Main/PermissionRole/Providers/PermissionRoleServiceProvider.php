<?php


namespace Main\PermissionRole\Providers;


use Illuminate\Support\ServiceProvider;

class PermissionRoleServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__. '/../Routes/permission_role_routes.php');
    }
}

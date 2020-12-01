<?php


namespace Main\Permission\Providers;


use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Main\Permission\Model\Permission;

class PermissionServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/permission_routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        Route::model('permission', Permission::class);

        $this->getBefore();
    }

    public function getBefore(): void
    {
        Gate::before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
            if (!Permission::hasClass($ability)) {
                return true;
            }

            return $user->checkPermission($ability);

        });
    }


}

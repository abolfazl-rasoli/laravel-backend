<?php


namespace Main\Follow\Providers;


use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Main\User\Model\User;

class FollowsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->modelBinding();
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__. '/../Routes/follows_routes.php');
    }

    private function modelBinding()
    {

        Route::model('from', User::class);
        Route::model('to', User::class);
    }

}

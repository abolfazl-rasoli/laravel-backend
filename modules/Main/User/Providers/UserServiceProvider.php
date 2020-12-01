<?php


namespace Main\User\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Main\User\Model\User;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->load();
        config()->set('auth.providers.users.model', User::class);
    }

    public function boot()
    {

        Passport::tokensExpireIn(now()->addDays(env('PASSPORT_EXPIRE_TOKEN_IN_DAY')));

        $this->modelBinding();

    }


    private function modelBinding()
    {

        Route::model('user_query', User::class);
    }

    public function load(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/user_routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadFactoriesFrom(__DIR__ . '/../Database/Factories');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'User');
    }

}

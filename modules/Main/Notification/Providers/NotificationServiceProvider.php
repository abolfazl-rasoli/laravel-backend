<?php


namespace Main\Notification\Providers;


use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Main\Notification\Model\Notification;

class NotificationServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadRoutesFrom(__DIR__. '/../Routes/notification_routes.php');

        Route::model('notification', Notification::class);

    }


}

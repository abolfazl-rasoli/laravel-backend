<?php


namespace Main\App\Provider;

use App;
use Illuminate\Support\ServiceProvider;
use Main\App\CommandLine\Provider\CommandLineServiceProvider;
use Main\App\Middleware\Provider\MiddlewareServiceProvider;
use Main\Notification\Providers\NotificationServiceProvider;
use Main\Uploader\Provider\UploaderServiceProvider;
use Main\Follow\Providers\FollowsServiceProvider;
use Main\Language\Providers\LanguageServiceProvider;
use Main\Permission\Providers\PermissionServiceProvider;
use Main\PermissionRole\Providers\PermissionRoleServiceProvider;
use Main\Role\Providers\RoleServiceProvider;
use Main\Setting\Providers\SettingServiceProvider;
use Main\User\Providers\UserServiceProvider;

class MainServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(UserServiceProvider::class);
        $this->app->register(RoleServiceProvider::class);
        $this->app->register(MiddlewareServiceProvider::class);
        $this->app->register(UploaderServiceProvider::class);
        $this->app->register(PermissionServiceProvider::class);
        $this->app->register(CommandLineServiceProvider::class);
        $this->app->register(PermissionRoleServiceProvider::class);
        $this->app->register(FollowsServiceProvider::class);
        $this->app->register(LanguageServiceProvider::class);
        $this->app->register(SettingServiceProvider::class);
        $this->app->register(NotificationServiceProvider::class);
    }

    public function boot()
    {

    }
}

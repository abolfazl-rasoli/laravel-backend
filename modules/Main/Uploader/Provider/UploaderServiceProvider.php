<?php


namespace Main\Uploader\Provider;

use Illuminate\Support\ServiceProvider;

class UploaderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app['config']['filesystems.disks.uploader'] = [
            'driver' => 'local',
            'root' => public_path('uploader'),
            'url' => env('APP_URL') . '/uploader',
            'visibility' => 'public',
        ];
        $this->app['config']['filesystems.disks.avatar'] = [
            'driver' => 'local',
            'root' => public_path('uploader/avatar'),
            'url' => env('APP_URL') . '/uploader/avatar',
            'visibility' => 'public',
        ];
        $this->app['config']['filesystems.disks.image'] = [
            'driver' => 'local',
            'root' => public_path('uploader/image'),
            'url' => env('APP_URL') . '/uploader/image',
            'visibility' => 'public',
        ];
        $this->app['config']['filesystems.disks.video'] = [
            'driver' => 'local',
            'root' => public_path('uploader/video'),
            'url' => env('APP_URL') . '/uploader/video',
            'visibility' => 'public',
        ];
        $this->app['config']['filesystems.disks.document'] = [
            'driver' => 'local',
            'root' => public_path('uploader/document'),
            'url' => env('APP_URL') . '/uploader/document',
            'visibility' => 'public',
        ];
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/uploader_routes.php');

    }
}

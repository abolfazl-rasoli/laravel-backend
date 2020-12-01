<?php


namespace Main\App\CommandLine\Provider;

use Illuminate\Support\ServiceProvider;

class CommandLineServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../main_console.php');
    }
}

<?php


namespace Main\Language\Providers;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Main\Language\Http\Observe\LanguageObserve;
use Main\Language\Model\Language;
use Main\Language\Translates\Translates;

class LanguageServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/language_routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        Route::model('language', Language::class);
        Language::observe(LanguageObserve::class);

        $this->createFileSystem();
        $this->checkHasMessagesFileInLangResource();
    }

    public function createFileSystem()
    {
        app()->config["filesystems.disks.translate"] = [
            'driver' => 'local',
            'root' => __DIR__ . '/../Translates/json',
            'visibility' => 'private',
        ];
        app()->config["filesystems.disks.langResource"] = [
            'driver' => 'local',
            'root' => resource_path('lang'),
            'visibility' => 'private',
        ];
    }

    private function checkHasMessagesFileInLangResource()
    {
        $storage = Storage::disk('langResource');
        if(Schema::hasTable('languages')){
            Language::all()->map(function ($item) use ($storage) {

                $filename = $item->lang . '/messages.php';

                if (!$storage->exists($filename)) {
                    $class = Translates::class;
                    $put = "<?php \n\nreturn $class::transByLocal('" . $item->lang . "');";
                    $storage->put($filename, $put, 'public');
                }

            });
        }
    }

}

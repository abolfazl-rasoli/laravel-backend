<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('make:modules {name}', function ($name) {
    /** @var ClosureCommand $this */
    $root = "modules/Main/$name";
    $nameSpace = "Main\\$name";

    $rootController = $root . "/Http/Controllers/" ;
    $nameController =  ucfirst($name) . "Controller";
    $nameSpaceController = $nameSpace. "\\Http\\Controllers";
    $fileController = $rootController. $nameController. '.php';

    $rootRequest = $root . "/Http/Requests/" ;
    $nameRequest =  ucfirst($name) . "Request";
    $nameSpaceRequest = $nameSpace. "\\Http\\Requests";
    $fileRequest = $rootRequest. $nameRequest. '.php';


    if (!file_exists($root)) {

        mkdir($root, 0775, true);
        mkdir($rootController, 0777, true);

        $contractFileContent = "<?php\n\nnamespace $nameSpaceController;\nuse App\Http\Controllers\Controller;
        \n\nclass $nameController extends Controller \n{\n}";
        file_put_contents($fileController, $contractFileContent);

        $this->info('Controller created successfully.');

        mkdir($rootRequest, 0777, true);

        $contractFileContent = "<?php\n\nnamespace $nameSpaceRequest;\nuse Main\App\Request\MainRequest;
        \n\nclass $nameRequest extends MainRequest \n{\n\n}";
        file_put_contents($fileRequest, $contractFileContent);

        $this->info('Request created successfully.');

    }

})->describe('Display an inspiring quote');

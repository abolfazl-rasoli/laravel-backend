<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Frontend\App\Http\Controllers',
    'middleware' => ['web', 'throttle'],
], function () {

    Route::get('/', [
        'as' => 'home',
        'uses' => 'HomeController@index'
    ]);

});

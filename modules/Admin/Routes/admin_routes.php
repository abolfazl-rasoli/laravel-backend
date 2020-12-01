<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Admin\App\Http\Controllers',
    'middleware' => ['web', 'throttle'],
    'prefix' => 'admin',
], function () {

    Route::any('{all}', function (){
        return view('Admin::admin');
    })->where('all', '.*');
    Route::any('/', function (){
        return view('Admin::admin');
    });;

});

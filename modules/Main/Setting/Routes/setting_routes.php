<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Main\Setting\Http\Controllers',
    'middleware' => ['api', 'Localization', 'throttle'],
    'prefix' => 'api',
], function () {

    //region setting
    Route::group(['prefix' => 'setting'] , function (){

        Route::group(['middleware' => ['custom_auth:api']], function (){
            Route::match(['put', 'post'], 'update', [
                'as' => 'setting.update',
                'uses' => 'SettingController@update'
            ]);
        });

        Route::match(['get', 'post'], 'view', [
            'as' => 'setting.view',
            'uses' => 'SettingController@view'
        ]);
    });
    //endregion
});

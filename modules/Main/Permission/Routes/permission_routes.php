<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Main\Permission\Http\Controllers',
    'middleware' => ['api', 'Localization', 'throttle'],
    'prefix' => 'api',
], function () {

    //region permission
    Route::group(['prefix' => 'permission'] , function (){

        Route::group(['middleware' => ['custom_auth:api']], function (){

            Route::get('view/{permission?}', [
                'as' => 'permission.view',
                'uses' => 'PermissionController@view'
            ]);

        });

    });
    //endregion
});

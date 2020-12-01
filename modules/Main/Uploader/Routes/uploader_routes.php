<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Main\Uploader\Http\Controllers',
    'middleware' => ['api', 'Localization', 'throttle'],
    'prefix' => 'api',
], function () {

    //region uploader
    Route::group(['prefix' => 'uploader'] , function (){

        Route::group(['middleware' => ['custom_auth:api']], function (){

            Route::match(['get', 'post'], 'view', [
                'as' => 'uploader.view',
                'uses' => 'UploaderController@view'
            ]);

            Route::match(['put', 'post'], 'upload', [
                'as' => 'uploader.upload',
                'uses' => 'UploaderController@upload'
            ]);

            Route::match(['delete', 'post'], 'delete', [
                'as' => 'uploader.delete',
                'uses' => 'UploaderController@delete'
            ]);

        });
    });
    //endregion
});

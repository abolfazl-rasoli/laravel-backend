<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Main\Notification\Http\Controllers',
    'middleware' => ['api', 'Localization', 'throttle'],
    'prefix' => 'api',
], function () {

    //region follow
    Route::group(['prefix' => 'notification'] , function (){

        Route::group(['middleware' => ['custom_auth:api']], function (){

            Route::get('me', [
                'as' => 'notification.me',
                'uses' => 'NotificationController@me'
            ]);

            Route::get('seen/{notification}', [
                'as' => 'notification.seen',
                'uses' => 'NotificationController@seen'
            ]);
        });

    });
    //endregion
});

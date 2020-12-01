<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Main\Follow\Http\Controllers',
    'middleware' => ['api', 'Localization', 'throttle'],
    'prefix' => 'api',
], function () {

    //region follow
    Route::group(['prefix' => 'follow'] , function (){

        Route::group(['middleware' => ['custom_auth:api']], function (){

            Route::get('attach/{to}', [
                'as' => 'follow.attach',
                'uses' => 'FollowsController@attach'
            ]);
        });

        Route::get('following/{user_query?}', [
            'as' => 'follow.following',
            'uses' => 'FollowsController@following'
        ]);
        Route::get('followers/{user_query?}', [
            'as' => 'follow.followers',
            'uses' => 'FollowsController@followers'
        ]);

    });
    //endregion
});

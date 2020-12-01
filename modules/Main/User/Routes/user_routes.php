<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Main\User\Http\Controllers',
    'middleware' => ['api', 'Localization', 'throttle'],
    'prefix' => 'api',
], function () {

    //region auth
    Route::group(['prefix' => 'auth'] , function (){

        Route::post('login', [
            'as' => 'auth.login',
            'uses' => 'AuthController@login'
        ]);
        Route::post('register', [
            'as' => 'auth.register',
            'uses' => 'AuthController@register'
        ]);
        Route::post('verify', [
            'as' => 'auth.verify',
            'uses' => 'AuthController@verify'
        ]);

        Route::group(['namespace' => '\Laravel\Passport\Http\Controllers'], function () {
            Route::post('login-passport', [
                'as' => 'auth.login.passport',
                'middleware' => ['throttle'],
                'uses' => 'AccessTokenController@issueToken',
            ]);
        });

    });
    //endregion

    //region user
    Route::group(['prefix' => 'user'] , function (){

        Route::group(['middleware' => ['custom_auth:api']], function (){

            Route::get('logout', [
                'as' => 'user.logout',
                'uses' => 'UserController@logout'
            ]);
            Route::match(['post', 'put'],'edit/username/verify', [
                'as' => 'user.edit.username.verify',
                'uses' => 'UserController@usernameVerify'
            ]);
            Route::match(['post', 'put'],'edit/username/{user_query?}', [
                'as' => 'user.edit.username',
                'uses' => 'UserController@usernameEdit'
            ]);
            Route::match(['post', 'put'],'edit/avatar/{user_query?}', [
                'as' => 'user.edit.avatar',
                'uses' => 'UserController@editAvatar'
            ]);
            Route::match(['post', 'put'],'edit/{user_query?}', [
                'as' => 'user.edit',
                'uses' => 'UserController@edit'
            ]);
            Route::post('create', [
                'as' => 'user.create',
                'uses' => 'UserController@create'
            ]);
            Route::match(['post', 'put'], 'status' , [
                'as' => 'user.status',
                'uses' => 'UserController@status'
            ]);
            Route::match(['post', 'delete'], 'delete' , [
                'as' => 'user.delete',
                'uses' => 'UserController@delete'
            ]);
            Route::post('search' , [
                'as' => 'user.search',
                'uses' => 'UserController@search'
            ]);

        });

        Route::get('me', [
            'as' => 'user.me',
            'uses' => 'UserController@me'
        ]);
        Route::post('forget-password' , [
            'as' => 'user.forget.password',
            'uses' => 'UserController@forgetPassword'
        ]);

    });
    //endregion

});

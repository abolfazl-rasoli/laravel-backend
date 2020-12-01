<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Main\Language\Http\Controllers',
    'middleware' => ['api', 'Localization', 'throttle'],
    'prefix' => 'api',
], function () {

    //region permission
    Route::group(['prefix' => 'language'] , function (){

        Route::group(['middleware' => ['custom_auth:api']], function (){

            Route::post('create', [
                'as' => 'language.create',
                'uses' => 'LanguageController@create'
            ]);
            Route::match(['put', 'post'],'edit/{language}', [
                'as' => 'language.edit',
                'uses' => 'LanguageController@edit'
            ]);
            Route::match(['delete', 'post'],'delete/{language}', [
                'as' => 'language.delete',
                'uses' => 'LanguageController@delete'
            ]);
            Route::match(['put', 'post'],'primary/{language}', [
                'as' => 'language.primary',
                'uses' => 'LanguageController@primary'
            ]);

        });

        Route::get('view', [
            'as' => 'language.view',
            'uses' => 'LanguageController@view'
        ]);

    });
    //endregion
});

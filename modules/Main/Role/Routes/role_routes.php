<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Main\Role\Http\Controllers',
    'middleware' => ['api', 'Localization', 'throttle'],
    'prefix' => 'api',
], function () {

    //region role
    Route::group(['prefix' => 'role'] , function (){

        Route::group(['middleware' => ['custom_auth:api']], function (){

            Route::get('view/{role?}', [
                'as' => 'role.view',
                'uses' => 'RoleController@view'
            ]);
            Route::post('create', [
                'as' => 'role.create',
                'uses' => 'RoleController@create'
            ]);
            Route::match(['put', 'post'], 'edit/{role}', [
                'as' => 'role.edit',
                'uses' => 'RoleController@edit'
            ]);
            Route::post( 'edit-trans/{role}', [
                'as' => 'role.edit.trans',
                'uses' => 'RoleController@editTrans'
            ]);
            Route::match(['delete', 'post'], 'delete/{role}', [
                'as' => 'role.delete',
                'uses' => 'RoleController@delete'
            ]);

        });

    });
    //endregion
});

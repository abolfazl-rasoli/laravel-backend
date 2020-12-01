<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Main\PermissionRole\Http\Controllers',
    'middleware' => ['api', 'Localization', 'throttle'],
    'prefix' => 'api',
], function () {

    //region permission_role
    Route::group(['prefix' => 'permission_role'] , function (){

        Route::group(['middleware' => ['custom_auth:api']], function (){

            Route::get('attach/{role}/{permission}', [
                'as' => 'permission.role.attach',
                'uses' => 'PermissionRoleController@attach'
            ]);
            Route::get('view/{role}', [
                    'as' => 'permission.role.view',
                    'uses' => 'PermissionRoleController@view'
            ]);

        });

    });
    //endregion
});

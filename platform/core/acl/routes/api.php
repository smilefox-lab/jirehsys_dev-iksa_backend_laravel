<?php

Route::group([
    'prefix'     => 'api/v1',
    'namespace'  => 'Botble\ACL\Http\Controllers\Api',
    'middleware' => 'api'
], function () {

    Route::group([
        'namespace'  => 'Auth',
    ], function () {
        Route::post('login', 'AuthenticationController@login');

        Route::post('password/forgot', 'ForgotPasswordController@sendResetLinkEmail');

        Route::post('password/reset', 'ResetPasswordController@reset');
    });

    Route::group([
        'middleware' => ['auth:api']
    ], function () {
        Route::get('logout', 'Auth\AuthenticationController@logout');
        Route::get('me', 'UserController@getProfile');
        // Route::post('update-avatar', 'VendorController@updateAvatar');
        // Route::put('change-password', 'VendorController@updatePassword');

        Route::group([
            'prefix' => 'companies', 'as' => 'api.company.'
        ], function () {

            Route::get('', [
                'as'         => 'index',
                'uses'       => 'CompanyController@index',
                'permission' => 'company.api.view',
            ]);

        });


        Route::group(['prefix' => 'company'], function () {
            Route::get('{id}/{fileName}', [
                'as'         => 'company.download-file',
                'uses'       => 'CompanyController@downloadFile',
                'permission' =>  false,
            ]);
        });
    });

});

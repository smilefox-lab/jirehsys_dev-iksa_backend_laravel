<?php

Route::group([
    'prefix'     => 'api/v1',
    'namespace'  => 'Botble\Vendor\Http\Controllers\API',
    'middleware' => ['api'],
], function () {

    Route::post('register', 'AuthenticationController@register');
    Route::post('login', 'AuthenticationController@login');

    Route::post('password/forgot', 'ForgotPasswordController@sendResetLinkEmail');

    Route::post('verify-account', 'VerificationController@verify');
    Route::post('resend-verify-account-email', 'VerificationController@resend');

    Route::group(['middleware' => ['auth:vendor-api']], function () {
        Route::get('logout', 'AuthenticationController@logout');
        Route::get('me', 'VendorController@getProfile');
        Route::put('me', 'VendorController@updateProfile');
        Route::post('update-avatar', 'VendorController@updateAvatar');
        Route::put('change-password', 'VendorController@updatePassword');
    });

});

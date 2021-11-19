<?php

Route::group([
    'namespace'  => 'Botble\Vendor\Http\Controllers',
    'prefix'     => config('core.base.general.admin_dir'),
    'middleware' => ['web', 'auth'],
], function () {

    Route::group(['prefix' => 'vendors', 'as' => 'vendor.'], function () {

        Route::resource('', 'VendorController')->parameters(['' => 'vendor']);

        Route::delete('items/destroy', [
            'as'         => 'deletes',
            'uses'       => 'VendorController@deletes',
            'permission' => 'vendor.destroy',
        ]);

        Route::get('list', [
            'as'         => 'list',
            'uses'       => 'VendorController@getList',
            'permission' => 'vendor.index',
        ]);

        Route::post('credits/{id}', [
            'as'         => 'credits.add',
            'uses'       => 'TransactionController@postCreate',
            'permission' => 'vendor.edit',
        ]);
    });

    Route::group(['prefix' => 'packages', 'as' => 'package.'], function () {
        Route::resource('', 'PackageController')->parameters(['' => 'package']);
        Route::delete('items/destroy', [
            'as'         => 'deletes',
            'uses'       => 'PackageController@deletes',
            'permission' => 'package.destroy',
        ]);
    });

});

if (defined('THEME_MODULE_SCREEN_NAME')) {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {

        Route::group([
            'namespace'  => 'Botble\Vendor\Http\Controllers',
            'middleware' => ['web'],
            'as'         => 'public.vendor.',
        ], function () {

            Route::group(['middleware' => ['vendor.guest']], function () {
                Route::get('login', 'LoginController@showLoginForm')->name('login');
                Route::post('login', 'LoginController@login')->name('login.post');

                Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
                Route::post('register', 'RegisterController@register')->name('register.post');

                Route::get('verify', 'RegisterController@getVerify')->name('verify');

                Route::get('password/request',
                    'ForgotPasswordController@showLinkRequestForm')->name('password.request');
                Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
                Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');
                Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
            });

            Route::group([
                'middleware' => [setting('verify_account_email', config('plugins.vendor.general.verify_email')) ? 'vendor.guest' : 'vendor'],
            ], function () {
                Route::get('register/confirm/resend',
                    'RegisterController@resendConfirmation')->name('resend_confirmation');
                Route::get('register/confirm/{email}', 'RegisterController@confirm')->name('confirm');
            });
        });

        Route::group([
            'namespace'  => 'Botble\Vendor\Http\Controllers',
            'middleware' => ['web', 'vendor'],
            'as'         => 'public.vendor.',
        ], function () {
            Route::group([
                'prefix' => 'account',
            ], function () {

                Route::post('logout', 'LoginController@logout')->name('logout');

                Route::get('dashboard', [
                    'as'   => 'dashboard',
                    'uses' => 'PublicController@getDashboard',
                ]);

                Route::get('settings', [
                    'as'   => 'settings',
                    'uses' => 'PublicController@getSettings',
                ]);

                Route::post('settings', [
                    'as'   => 'post.settings',
                    'uses' => 'PublicController@postSettings',
                ]);

                Route::get('security', [
                    'as'   => 'security',
                    'uses' => 'PublicController@getSecurity',
                ]);

                Route::put('security', [
                    'as'   => 'post.security',
                    'uses' => 'PublicController@postSecurity',
                ]);

                Route::post('avatar', [
                    'as'   => 'avatar',
                    'uses' => 'PublicController@postAvatar',
                ]);

                Route::get('packages', [
                    'as'   => 'packages',
                    'uses' => 'PublicController@getPackages',
                ]);

                Route::get('transactions', [
                    'as'   => 'transactions',
                    'uses' => 'PublicController@getTransactions',
                ]);

            });

            Route::group(['prefix' => 'ajax/vendors'], function () {
                Route::get('activity-logs', [
                    'as'   => 'activity-logs',
                    'uses' => 'PublicController@getActivityLogs',
                ]);

                Route::get('transactions', [
                    'as'   => 'ajax.transactions',
                    'uses' => 'PublicController@ajaxGetTransactions',
                ]);

                Route::post('upload', [
                    'as'   => 'upload',
                    'uses' => 'PublicController@postUpload',
                ]);

                Route::post('upload-from-editor', [
                    'as'   => 'upload-from-editor',
                    'uses' => 'PublicController@postUploadFromEditor',
                ]);
            });

            Route::group(['prefix' => 'account/properties', 'as' => 'properties.'], function () {
                Route::resource('', 'PropertyController')->parameters(['' => 'property']);

                Route::post('renew/{id}', [
                    'as'   => 'renew',
                    'uses' => 'PropertyController@renew',
                ]);
            });

            Route::group(['prefix' => 'ajax/vendor'], function () {
                Route::get('packages', 'PublicController@ajaxGetPackages')->name('ajax.packages');
                Route::put('packages', 'PublicController@ajaxSubscribePackage')->name('ajax.package.subscribe');
            });

            Route::group(['prefix' => 'vendor'], function () {
                Route::get('packages/{id}/subscribe',
                    'PublicController@getSubscribePackage')->name('package.subscribe');
                Route::get('packages/{id}/subscribe/callback',
                    'PublicController@getPackageSubscribeCallback')->name('package.subscribe.callback');
            });
        });

    });
}

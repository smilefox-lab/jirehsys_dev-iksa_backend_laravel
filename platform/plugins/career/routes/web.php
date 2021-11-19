<?php

Route::group(['namespace' => 'Botble\Career\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {

        Route::resource('careers', 'CareerController', ['names' => 'career']);

        Route::group(['prefix' => 'careers'], function () {

            Route::delete('items/destroy', [
                'as'         => 'career.deletes',
                'uses'       => 'CareerController@deletes',
                'permission' => 'career.destroy',
            ]);
        });
    });

});

<?php

Route::group(['namespace' => 'Botble\RealEstate\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => 'contracts', 'as' => 'contract.'], function () {
        Route::get('list', [
            'as'         => 'list',
            'uses'       => 'ContractController@getList',
            'permission' => 'contract.index',
        ]);

        Route::get('property/{property}/contracts', [
            'as'         => 'get-contracts-by-property',
            'uses'       => 'ContractController@getContratcsByProperty',
            'permission' => 'contract.index',
        ]);
    });


    Route::group(['prefix' => config('core.base.general.admin_dir') . '/real-estate', 'middleware' => 'auth'], function () {

        Route::get('settings', [
            'as'   => 'real-estate.settings',
            'uses' => 'RealEstateController@getSettings',
        ]);

        Route::post('settings', [
            'as'   => 'real-estate.settings',
            'uses' => 'RealEstateController@postSettings',
        ]);

        Route::group(['prefix' => 'properties', 'as' => 'property.'], function () {
            Route::resource('', 'PropertyController')->parameters(['' => 'property']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'PropertyController@deletes',
                'permission' => 'property.destroy',
            ]);
        });

        Route::group(['prefix' => 'property-features', 'as' => 'property_feature.'], function () {
            Route::resource('', 'FeatureController')->parameters(['' => 'property_feature']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'FeatureController@deletes',
                'permission' => 'property_feature.destroy',
            ]);
        });

        Route::group(['prefix' => 'types', 'as' => 'type.'], function () {
            Route::resource('', 'TypeController')->parameters(['' => 'type']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'TypeController@deletes',
                'permission' => 'type.destroy',
            ]);
        });

        Route::group(['prefix' => 'lessees', 'as' => 'lessee.'], function () {
            Route::resource('', 'LesseeController')->parameters(['' => 'lessee']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'LesseeController@deletes',
                'permission' => 'lessee.destroy',
            ]);
        });

        Route::group(['prefix' => 'contracts', 'as' => 'contract.'], function () {
            Route::resource('', 'ContractController')->parameters(['' => 'contract']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'ContractController@deletes',
                'permission' => 'contract.destroy',
            ]);
        });

        Route::group(['prefix' => 'payments', 'as' => 'payment.'], function () {
            Route::resource('', 'PaymentController')->parameters(['' => 'payment']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'PaymentController@deletes',
                'permission' => 'payment.destroy',
            ]);
        });

        Route::get('import', [
            'as'   => 'real-estate.import',
            'uses' => 'ImportController@index',
            'permission' => 'import.index',
        ]);

        Route::post('import', [
            'as'   => 'real-estate.import',
            'uses' => 'ImportController@import',
            'permission' => 'import.index',
        ]);
    });

});

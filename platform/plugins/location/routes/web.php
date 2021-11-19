<?php

Route::group(['namespace' => 'Botble\Location\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => 'countries', 'as' => 'country.'], function () {
        Route::get('list', [
            'as'         => 'list',
            'uses'       => 'CountryController@getList',
            'permission' => 'country.index',
        ]);
    });

    Route::group(['prefix' => 'states', 'as' => 'state.'], function () {
        Route::get('list', [
            'as'         => 'list',
            'uses'       => 'StateController@getList',
            'permission' => 'state.index',
        ]);

        Route::get('get-states', [
            'as'   => 'get-states',
            'uses' => 'StateController@getArrayStates',
        ]);
    });

    Route::group(['prefix' => 'cities', 'as' => 'city.'], function () {
        Route::get('list', [
            'as'         => 'list',
            'uses'       => 'CityController@getList',
            'permission' => 'city.index',
        ]);

        Route::get('get-cities-by-state', [
            'as'   => 'get-cities-by-state',
            'uses' => 'CityController@getCitiesByStateId',
        ]);
    });

    Route::group(['prefix' => 'regions', 'as' => 'region.'], function () {
        Route::get('list', [
            'as'         => 'list',
            'uses'       => 'RegionController@getList',
            'permission' => 'region.index',
        ]);

        Route::get('get-regions-by-country', [
            'as'   => 'get-regions-by-country',
            'uses' => 'RegionController@getRegionsByCountryId',
        ]);
    });

    Route::group(['prefix' => 'communes', 'as' => 'commune.'], function () {
        Route::get('list', [
            'as'         => 'list',
            'uses'       => 'CommuneController@getList',
            'permission' => 'commune.index',
        ]);

        Route::get('get-communes-by-region', [
            'as'   => 'get-communes-by-region',
            'uses' => 'CommuneController@getCommunesByRegionId',
        ]);
    });

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'countries', 'as' => 'country.'], function () {
            Route::resource('', 'CountryController')->parameters(['' => 'country']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'CountryController@deletes',
                'permission' => 'country.destroy',
            ]);
        });

        Route::group(['prefix' => 'states', 'as' => 'state.'], function () {
            Route::resource('', 'StateController')->parameters(['' => 'state']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'StateController@deletes',
                'permission' => 'state.destroy',
            ]);
        });

        Route::group(['prefix' => 'cities', 'as' => 'city.'], function () {
            Route::resource('', 'CityController')->parameters(['' => 'city']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'CityController@deletes',
                'permission' => 'city.destroy',
            ]);
        });

        Route::group(['prefix' => 'regions', 'as' => 'region.'], function () {
            Route::resource('', 'RegionController')->parameters(['' => 'region']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'RegionController@deletes',
                'permission' => 'region.destroy',
            ]);
        });

        Route::group(['prefix' => 'communes', 'as' => 'commune.'], function () {
            Route::resource('', 'CommuneController')->parameters(['' => 'commune']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'CommuneController@deletes',
                'permission' => 'commune.destroy',
            ]);
        });
    });

});

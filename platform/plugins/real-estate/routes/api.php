<?php

Route::group([
    'prefix'     => 'api/v1/real-estate',
    'namespace'  => 'Botble\RealEstate\Http\Controllers\Api',
    'middleware' => ['api', 'auth:api']
], function () {

    Route::group([
        'prefix' => 'properties', 'as' => 'api.property.'
    ], function () {

        Route::get('list', [
            'as'         => 'list',
            'uses'       => 'PropertyController@list',
            'permission' => 'api.property',
        ]);

        Route::get('payments', [
            'as'         => 'payments',
            'uses'       => 'PropertyController@payment',
            'permission' => 'api.property',
        ]);

        Route::get('/status', [
            'as'         => 'status',
            'uses'       => 'PropertyController@status',
            'permission' => 'api.property',
        ]);

        Route::get('/type/general', [
            'as'         => 'type_general',
            'uses'       => 'PropertyController@typeGeneral',
            'permission' => 'api.property',
        ]);

        Route::get('/type/company', [
            'as'         => 'type_by_company',
            'uses'       => 'PropertyController@typeByCompany',
            'permission' => 'api.property',
        ]);

        Route::get('/type/status', [
            'as'         => 'type_status',
            'uses'       => 'PropertyController@typeStatus',
            'permission' => 'api.property',
        ]);

        Route::get('/{property}', [
            'as'         => 'show',
            'uses'       => 'PropertyController@show',
            'permission' => 'api.property',
        ]);

        Route::get('{id}/{folderId}/{fileName}', [
            'as'         => 'status',
            'uses'       => 'PropertyController@downloadFile',
            'permission' => 'api.property',
        ]);
    });

    Route::group([
        'prefix' => 'leases', 'as' => 'api.lease.'
    ], function () {
        Route::get('list', [
            'as'         => 'list',
            'uses'       => 'LeaseController@list',
            'permission' => 'api.lease',
        ]);

        Route::get('holding', [
            'as'         => 'by-holding',
            'uses'       => 'LeaseController@byHolding',
            'permission' => 'api.lease',
        ]);

        Route::get('indicators', [
            'as'         => 'indicators',
            'uses'       => 'LeaseController@indicators',
            'permission' => 'api.lease',
        ]);

        Route::get('companies-indicators', [
            'as'         => 'companies-indicators',
            'uses'       => 'LeaseController@companiesIndicators',
            'permission' => 'api.lease',
        ]);

        Route::get('history', [
            'as'         => 'by-month-year',
            'uses'       => 'LeaseController@byHistory',
            'permission' => 'api.lease',
        ]);
    });

    Route::group([
        'prefix' => 'debtors', 'as' => 'api.debtor.'
    ], function () {
        Route::get('list', [
            'as'         => 'list',
            'uses'       => 'DebtorController@list',
            'permission' => 'api.lease',
        ]);

        Route::get('top', [
            'as'         => 'top',
            'uses'       => 'DebtorController@getTop',
            'permission' => 'api.debtor',
        ]);

        Route::get('overview', [
            'as'         => 'overview',
            'uses'       => 'DebtorController@byOverview',
            'permission' => 'api.lease',
        ]);

        Route::get('history-graph', [
            'as'         => 'history-graph',
            'uses'       => 'DebtorController@byHistoryGraphDefaultAndDelay',
            'permission' => 'api.lease',
        ]);
    });

    Route::group([
        'prefix' => 'types', 'as' => 'api.type.'
    ], function () {
        Route::get('list', [
            'as'         => 'list',
            'uses'       => 'TypeController@list',
            'permission' => 'api.type',
        ]);
    });

    Route::group([
        'prefix' => 'alerts', 'as' => 'api.alert.'
    ], function () {
        Route::get('list', [
            'as'         => 'list',
            'uses'       => 'AlertController@list',
            'permission' => 'api.alert',
        ]);
    });

    Route::group([
        'prefix' => 'indicators', 'as' => 'api.indicator.'
    ], function () {

        Route::get('profitability', [
            'as'         => 'profitability',
            'uses'       => 'IndicatorController@profitability',
            'permission' => 'api.property',
        ]);
    });
});

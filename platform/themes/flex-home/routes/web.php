<?php

Route::group(['namespace' => 'Theme\FlexHome\Http\Controllers', 'middleware' => 'web'], function () {

    // Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
    //     Route::get('projects', 'FlexHomeController@getProjects')->name('public.projects');
    //     Route::get('projects/{slug}', 'FlexHomeController@getProject')->name('public.project');
    //     Route::get('projects/city/{slug?}', 'FlexHomeController@getProjectsByCity')->name('public.project-by-city');
    //     Route::get('properties/city/{slug?}', 'FlexHomeController@getPropertiesByCity')->name('public.properties-by-city');
    //     Route::get('properties', 'FlexHomeController@getProperties')->name('public.properties');
    //     Route::get('properties/{slug}', 'FlexHomeController@getProperty')->name('public.property');
    //     Route::get('contact', 'FlexHomeController@contact')->name('public.contact');
    //     Route::get('careers', 'FlexHomeController@careers')->name('public.careers');
    //     Route::get('careers/{slug}', 'FlexHomeController@career')->name('public.career');
    // });

    // Theme::routes();

    // Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {

    Route::get('/', function(Request $request) {
        return redirect('/admin');
    })->name('public.index');

    //     Route::get('sitemap.xml', [
    //         'as'   => 'public.sitemap',
    //         'uses' => 'FlexHomeController@getSiteMap',
    //     ]);

    //     Route::get('{slug?}' . config('core.base.general.public_single_ending_url'), [
    //         'as'   => 'public.single',
    //         'uses' => 'FlexHomeController@getView',
    //     ]);

    //     Route::get('ajax/properties', 'FlexHomeController@ajaxGetProperties')->name('public.ajax.properties');
    //     Route::get('ajax/posts', 'FlexHomeController@ajaxGetPosts')->name('public.ajax.posts');

    // });

});

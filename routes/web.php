<?php

Route::group(['middleware' => 'auth'], function ()
{
    Route::get('content', 'ContentController@getIndex');
    Route::get('content/edit', 'ContentController@getEdit');
    Route::post('content/edit', 'ContentController@postEdit');
    Route::get('content/edit/{id}', 'ContentController@getEdit');
    Route::post('content/edit/{id}', 'ContentController@postEdit');
    Route::get('content/delete/{id}', 'ContentController@getDelete');
    Route::post('content/delete/{id}', 'ContentController@postDelete');

});

Route::get('/', 'WelcomeController@getIndex');
Route::post('/', 'WelcomeController@postIndex');

Route::get('auth/login', 'AuthController@getLogin');
Route::post('auth/login', 'AuthController@postLogin');
Route::get('auth/logout', 'AuthController@getLogout');

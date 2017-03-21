<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();
Route::get('/', 'HomeController@index')->middleware('auth');
Route::get('/home', 'HomeController@index')->middleware('auth');

Route::get('/users', 'UserController@index')->middleware('permission:settings');
Route::get('/user/{user}/edit', 'UserController@edit')->middleware('permission:settings');
Route::post('/user/{user}/update', 'UserController@update')->middleware('permission:settings');
Route::get('/user/create', 'UserController@create')->middleware('permission:settings');
Route::post('/user/store', 'UserController@store')->middleware('permission:settings');

Route::get('/settings', 'SettingsController@index')->middleware('permission:settings');
Route::get('/settings/login-logs', 'SettingsController@getUserLoginLogs')->middleware('permission:settings');
Route::get('/settings/change-logs', 'SettingsController@getUserChangeLogs')->middleware('permission:settings');
Route::get('/help', 'SettingsController@getHelp');

Route::get('/uploads', 'UploadController@index')->middleware('permission:uploadFile');
Route::get('/upload/create', 'UploadController@create')->middleware('permission:uploadFile');
Route::post('/upload/store', 'UploadController@store')->middleware('permission:uploadFile');
Route::post('/upload/destroy/{file}', 'UploadController@destroy')->middleware('permission:uploadFile');

Route::get('/clients', 'ClientController@index')->middleware('permission:clientLookup');
Route::get('/client/{client}', 'ClientController@show')->middleware('permission:clientLookup');

Route::get('/reports', 'ReportController@index')->middleware('permission:reports');
Route::get('/reports/spend-by-client', 'ReportController@getSpendByClient')->middleware('permission:reports');
Route::get('/reports/spend-by-service', 'ReportController@getSpendByService')->middleware('permission:reports');
Route::get('/reports/client-spend', 'ReportController@getClientSpend')->middleware('permission:reports');

Route::get('/filetypes', 'FileTypeController@index')->middleware('permission:settings');
Route::get('/filetype/{filetype}/edit', 'FileTypeController@edit')->middleware('permission:settings');
Route::post('/filetype/{filetype}/update', 'FileTypeController@update')->middleware('permission:settings');
Route::get('/filetype/create', 'FileTypeController@create')->middleware('permission:settings');
Route::post('/filetype/store', 'FileTypeController@store')->middleware('permission:settings');

Route::get('/providers', 'ProviderController@index')->middleware('permission:settings');
Route::get('/provider/{provider}/edit', 'ProviderController@edit')->middleware('permission:settings');
Route::post('/provider/{provider}/update', 'ProviderController@update')->middleware('permission:settings');
Route::get('/provider/create', 'ProviderController@create')->middleware('permission:settings');
Route::post('/provider/store', 'ProviderController@store')->middleware('permission:settings');
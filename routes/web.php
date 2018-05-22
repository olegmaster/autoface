<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/map', 'MapController@index')->name('map');

Route::resource('/device', 'DeviceController');

Route::get('/device-path/{id}','DeviceController@getPathOfDevice');

Route::get('/profile', 'UserController@edit')->name('profile');

Route::post('/user/update', 'UserController@update');

Route::get('/image/get/{deviceId}/{cameraId}/{page}', 'ImageController@getImage');

Route::post('/api/image/save', 'ApiController@imageSave');

Route::post('/api/video/save', 'ApiController@videoSave');

Route::get('/api/video/list', 'ApiController@videoList');

Route::post('/api/video/required', 'ApiController@setVideoRequired');

Route::get('/zone/get-all/{deviceId}', 'ZoneController@getAll');

Route::post('/zone/add', 'ZoneController@add');

Route::post('/zone/change', 'ZoneController@change');


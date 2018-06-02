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

Route::get('/device/get-for-map/{id}', 'DeviceController@getDeviceForMap');

Route::get('/get-all-device-for-map', 'DeviceController@getAllDevices');

Route::post('/put-device-on-alarm', 'DeviceController@putDeviceOnAlarm');

Route::post('/takeof-device-on-alarm', 'DeviceController@takeOfDeviceFromAlarm');

Route::post('/check/alarm', 'DeviceController@checkAlarm');

Route::get('/profile', 'UserController@edit')->name('profile');

Route::post('/user/update', 'UserController@update');

Route::get('/user/find/{login}', 'UserController@findUser');

Route::post('/image/get', 'ImageController@getImage');



Route::get('/api/video/list/{serialNumber}/{password}', 'ApiController@videoList');

Route::get('/api/alarm-video/list/{serialNumber}/{password}','ApiController@alarmVideoList');

Route::get('/api/alarm-situation-date/{serialNumber}/{password}', 'ApiController@alarmSituationDate');

Route::post('/api/video/required', 'ApiController@setVideoRequired');

Route::post('/api/alarm-video/required', 'ApiController@setAlarmVideoRequired');

Route::post('/api/image/save', 'ApiController@imageSave');

Route::post('/api/video/save', 'ApiController@videoSave');

Route::get('/api-get-position-by-image/{id}', 'ApiController@getPositionByImage');


Route::get('/zone/get-all/{deviceId}', 'ZoneController@getAll');

Route::post('/zone/add', 'ZoneController@add');

Route::post('/zone/change', 'ZoneController@change');



Route::post('/message/handle', 'MessageController@handleMessage');

Route::get('/users/get/all', 'UserController@getAllUsers');

Route::get('/get-affiliated-devices', 'DeviceController@getAffiliatedDevices');

Route::post('/affiliation/add', 'AffiliationController@add');

Route::post('/bind-affiliation-zone', 'AffiliationController@bindZoneToAffilation');

Route::get('/get-affilations-for-confirm', 'AffiliationController@getAffiliationsToConfirm');

Route::get('/confirm-affiliation/{id}', 'AffiliationController@confirmAlliliation');

Route::get('/reject-affiliation/{id}', 'AffiliationController@rejectAffiliation');


Route::resource('/affiliate-user', 'AffiliateUserController');

Route::get('/delete-affiliation/{id}', 'AffiliateUserController@destroy');

Route::post('/affiliate-user-search', 'AffiliateUserController@search');

Route::get('/bond-devices-on-connection', 'DeviceController@bondDevicesOnConnection');


Route::get('/situations', 'AlarmSituationController@index');
Route::get('/situations-show/{id}', 'AlarmSituationController@show');


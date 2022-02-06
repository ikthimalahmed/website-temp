<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['cors'])->group(function () {
    Route::post('/hogehoge', 'Controller@hogehoge');
});

Route::group(['prefix' => 'booking'], function () {
    Route::post('CreateBooking', 'BookingController@CreateBooking');
    Route::put('update/{id}', 'BookingController@update');
    Route::get('getMyCalendar', 'BookingController@getMyCalendar');
    Route::get('getData', 'BookingController@getData');
    Route::get('mailList/{id}', 'BookingController@mailList');
    Route::post('sendMail', 'BookingController@sendMail');
    Route::post('sendAll/{booking}', 'BookingController@sendAll');
});

// rest api routes
Route::get("{resType}/validations",'\Modules\RestShared\RestSharedController@rsShowRules');
Route::get("{resType}", '\Modules\RestShared\RestSharedController@index')->name('base-res.list');
Route::post("{resType}", '\Modules\RestShared\RestSharedController@store')->name('base-res.create');
Route::get("{resType}/{id}", '\Modules\RestShared\RestSharedController@rsShow')->name('api_get');
Route::patch("{resType}/{id}/_actions", '\Modules\RestShared\RestSharedController@rsDoAction');
Route::put("{resType}/{id}", '\Modules\RestShared\RestSharedController@rsUpdate');
Route::delete("{resType}/{id}", '\Modules\RestShared\RestSharedController@rsDestroy');

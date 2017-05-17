<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});

Route::get('/test', function () {
  return "ok";
});

Route::group(['prefix' => '/v1'], function () {
  // Routes which require auth
  Route::group([
    "middleware" => ['jwt.auth'],
  ], function () {
    
    Route::get('offers-for-user', 'OfferController@getUsersOffers'); //get offers by user
    Route::get('offers-for-date', 'OfferController@getDatesOffers'); //get offers by date
    Route::post('nearby-offers', 'OfferController@getNearby'); //POST the coords and return nearby offers

    Route::get('bookings-for-user', 'BookingController@getUsersBookings'); //get bookings by user
    Route::get('bookings-for-offer', 'BookingController@getOffersBookings'); //get bookings to an offer
    
    Route::resource('offers', 'OfferController', ['except' => [
      'create', 'edit',
    ]]);

    Route::resource('bookings', 'BookingController', ['except' => [
      'create', 'edit', 'update',
    ]]);

    //User info
    Route::get('me', 'AuthenticateController@getAuthenticatedUser');

    //Push Notifications
    Route::resource('devices','NotificationController', [ 'only' => [ 'store', 'delete']]);
    Route::post('test-notification', 'NotificationController@sendTestNotification');
    Route::get('devices-for-user', 'NotificationController@getUsersDevices');
  });

  // Routes which does not require auth
  Route::post('auth', 'AuthenticateController@auth');
  Route::get('auth/refresh', 'AuthenticateController@refresh');
});

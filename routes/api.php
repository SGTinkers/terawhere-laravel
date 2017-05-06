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
    Route::resource('offers', 'OfferController', ['except' => [
      'create', 'edit',
    ]]);
    Route::resource('bookings', 'BookingController', ['except' => [
      'create', 'edit', 'update',
    ]]);

    Route::get('offers/user', 'OfferController@getUsersOffers'); 
    Route::get('offers/today', 'OfferController@getDatesOffers');
    Route::get('offers/date', 'OfferController@getDatesOffers');

    Route::get('bookings/user', 'BookingController@getUsersBookings');
    Route::get('bookings/offer', 'BookingController@getOffersBookings');
    Route::get('bookings/date', 'BookingController@getDatesBookings'); //get date's bookings
    Route::get('bookings/today', 'BookingController@getDatesBookings'); //get date's bookings

    Route::post('offers/nearby', 'OfferController@getNearby'); //POST the coords and return nearby offers

    Route::get('me', 'AuthenticateController@getAuthenticatedUser');
  });

  // Routes which does not require auth
  Route::post('auth', 'AuthenticateController@auth');
  Route::get('auth/refresh', 'AuthenticateController@refresh');
});

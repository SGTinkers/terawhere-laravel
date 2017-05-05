<?php

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

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
  // Faruq: For testing social login
//  Route::get('/auth/callback', function (Request $request) {
//    $user = Socialite::driver($request->get("service"))->stateless()->user();
//    dd($user);
//    return "ok";
//  });

  // Routes which require auth
  Route::group([
    "middleware" => ['jwt.auth']
  ], function() {
    Route::resource('offers', 'OfferController');
    Route::resource('bookings', 'BookingController');

    Route::get('offers/user/{user}', 'OfferController@getUsersOffers');
    Route::get('bookings/user/{user}', 'BookingController@getUsersBookings');
    Route::get('bookings/offer/{offer}', 'BookingController@getOffersBookings');

    Route::post('offers/nearby', 'OfferController@getNearby'); //POST the coords and return nearby offers

    Route::get('authenticate/user', 'AuthenticateController@getAuthenticatedUser');
  });

  // Routes which does not require auth
  Route::post('authenticate', 'AuthenticateController@authenticate');
});

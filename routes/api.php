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
    
    //Offers
    Route::get('offers-for-user', 'OfferController@getUsersOffers'); //get offers by user
    Route::get('offers-for-date', 'OfferController@getDatesOffers'); //get offers by date
    Route::post('nearby-offers', 'OfferController@getNearby'); //POST the coords and return nearby offers

    //Bookings
    Route::get('bookings-for-user', 'BookingController@getUsersBookings'); //get bookings by user
    Route::get('bookings-for-offer', 'BookingController@getOffersBookings'); //get bookings to an offer
    
    //Offer Resource
    Route::resource('offers', 'OfferController', ['except' => [
      'create', 'edit',
    ]]);

    //Booking Resource
    Route::resource('bookings', 'BookingController', ['except' => [
      'create', 'edit', 'update',
    ]]);

    //User Info
    Route::get('me', 'AuthenticateController@getAuthenticatedUser');

    //Push Notifications
    Route::resource('devices','NotificationController', [ 'only' => [ 'store', 'delete']]);
    Route::post('test-notification', 'NotificationController@sendTestNotification');
    Route::get('devices-for-user', 'NotificationController@getUsersDevices');

    //Review Resource
    Route::resource('reviews', 'ReviewController', [ 'only' => ['index', 'store', 'show']]);
    Route::get('reviews-for-user', 'ReviewController@getUsersReviews');       //Reviews OF the user
    //Route::get('ratings-for-user', 'ReviewController@getUsersRatings');
    
    //Review analytics
    Route::get('reviewer-reviews', 'ReviewController@getReviewersReviews');   //Reviews WRITTEN BY USER

  });

  // Routes which does not require auth
  Route::post('auth', 'AuthenticateController@auth');
  Route::get('auth/refresh', 'AuthenticateController@refresh');
});

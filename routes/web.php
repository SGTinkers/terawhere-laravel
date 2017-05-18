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

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
  return view('welcome');
});

// Faruq: For testing social login
// TODO: Disable in production
Route::get('/auth/login', function (Request $request) {
  $service = $request->get("service");
  if (!$service) {
    return "provide a service in the GET request: `facebook` or `google`";
  }
  return Socialite::driver($service)->stateless()->redirect();
});

Route::get('/auth/callback', function (Request $request) {
  $user = Socialite::driver($request->get("service"))->stateless()->user();
  dd($user);
  return "ok";
});

<?php

namespace Tests\Feature;

use App\Booking;
use App\User;
use Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use JWTAuth;
use Tests\TestCase;

class BookingTest extends TestCase
{
  use DatabaseTransactions;

  /**
   * Test /api/v1/bookings
   * Return with results
   *
   * @return void
   */
  public function testIndex()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);

    $response = $this->json('GET', '/api/v1/bookings', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertJsonFragment([
        'data'  => Booking::all()->toArray(),
      ]);
  }

  /**
   * Test /api/v1/bookings
   * Return no results
   *
   * @return void
   */
  public function testIndexNoResults()
  {

//    $user  = User::where('email', 'user_no_bookings@test.com')->first();
//    if (!$user) {
//      $user = new User;
//      $user->email = 'user_no_bookings@test.com';
//      $user->name = 'No bookings user';
//      $user->password = Hash::make('1234');
//      $user->dp = 'https://www.gravatar.com/avatar/5e551173b6c2eec67dd4ee697d51ebde';
//      $user->save();
//    }
    $user  = User::first();
    $token = JWTAuth::fromUser($user);

    // It's okay to do this because we are using DatabaseTransactions
    // Changes will be rollback-ed after test
    Booking::where('id', 'like', '%%')->delete();

    $response = $this->json('GET', '/api/v1/bookings', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertExactJson([
        'data'  => [],
      ]);
  }

  /**
   * Test /api/v1/bookings/{id}
   * Return with result
   *
   * @return void
   */
  public function testShow()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);
    $booking = Booking::first();

    $response = $this->json('GET', '/api/v1/bookings/' . $booking->id, [], ['Authorization' => 'Bearer ' . $token]);

    $data = $booking->toArray();
    $data['name']   = $user->name;
    $data['gender'] = $user->gender;

    $response
      ->assertStatus(200)
      ->assertExactJson([
        'data'  => $data,
      ]);
  }

  /**
   * Test /api/v1/bookings/{id}
   * Return with no result
   *
   * @return void
   */
  public function testShowNoResult()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);
    $response = $this->json('GET', '/api/v1/bookings/0', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(404)
      ->assertJson([
        'error'   => 'booking_not_found',
      ]);
  }
}

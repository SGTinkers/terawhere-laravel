<?php

namespace Tests\Feature;

use App\Booking;
use App\Offer;
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

  /**
   * Test POST /api/v1/bookings
   * Return success
   *
   * @return void
   */
  public function testStore()
  {
    $user  = User::where('email', 'user_no_bookings@test.com')->first();
    if (!$user) {
      $user = new User;
      $user->email = 'user_no_bookings@test.com';
      $user->name = 'No bookings user';
      $user->password = Hash::make('1234');
      $user->dp = 'https://www.gravatar.com/avatar/5e551173b6c2eec67dd4ee697d51ebde';
      $user->save();
    }
    $token = JWTAuth::fromUser($user);

    // Find an offer which has vacancies and does not belong to $user
    $offer = Offer::doesntHave('bookings')->where('user_id', '!=', $user->id)->first();

    $data = [
      'offer_id' => $offer->id,
    ];
    $response = $this->json('POST', '/api/v1/bookings', $data, ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'message' => true,
        'data' => true,
      ]);
  }

  /**
   * Test POST /api/v1/bookings
   * Offer not found
   *
   * @return void
   */
  public function testStoreOfferNotFound()
  {
    $user  = User::where('email', 'user_no_bookings@test.com')->first();
    if (!$user) {
      $user = new User;
      $user->email = 'user_no_bookings@test.com';
      $user->name = 'No bookings user';
      $user->password = Hash::make('1234');
      $user->dp = 'https://www.gravatar.com/avatar/5e551173b6c2eec67dd4ee697d51ebde';
      $user->save();
    }
    $token = JWTAuth::fromUser($user);
    $data = [
      'offer_id' => 0,
    ];
    $response = $this->json('POST', '/api/v1/bookings', $data, ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(422)
      ->assertJson([
        'error'   => 'offer_not_found',
      ]);
  }

  /**
   * Test POST /api/v1/bookings
   * Own offer
   *
   * @return void
   */
  public function testStoreOwnOffer()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);
    $data = [
      'offer_id' => Offer::where('user_id', $user->id)->first()->id,
    ];
    $response = $this->json('POST', '/api/v1/bookings', $data, ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(422)
      ->assertJson([
        'error'   => 'cannot_book_own_offer',
      ]);
  }

  /**
   * Test POST /api/v1/bookings
   * No vacancy
   *
   * @return void
   */
  public function testStoreNoVacancy()
  {
    $user  = User::where('email', 'user_no_bookings@test.com')->first();
    if (!$user) {
      $user = new User;
      $user->email = 'user_no_bookings@test.com';
      $user->name = 'No bookings user';
      $user->password = Hash::make('1234');
      $user->dp = 'https://www.gravatar.com/avatar/5e551173b6c2eec67dd4ee697d51ebde';
      $user->save();
    }
    $token = JWTAuth::fromUser($user);
    // Find an offer which has no vacancies
    $offer = Offer::has('bookings')->where('user_id', '!=', $user->id)->first();
    $data = [
      'offer_id' => $offer->id,
    ];
    $response = $this->json('POST', '/api/v1/bookings', $data, ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(422)
      ->assertJson([
        'error'   => 'no_more_vacancy',
      ]);
  }

  /**
   * Test POST /api/v1/bookings
   * Book twice
   *
   * @return void
   */
  public function testStoreBookTwice()
  {
    $user  = User::where('email', 'user_no_bookings@test.com')->first();
    if (!$user) {
      $user = new User;
      $user->email = 'user_no_bookings@test.com';
      $user->name = 'No bookings user';
      $user->password = Hash::make('1234');
      $user->dp = 'https://www.gravatar.com/avatar/5e551173b6c2eec67dd4ee697d51ebde';
      $user->save();
    }
    $token = JWTAuth::fromUser($user);

    // Find an offer which has vacancies and does not belong to $user
    $offer = Offer::doesntHave('bookings')->where('user_id', '!=', $user->id)->first();

    $data = [
      'offer_id' => $offer->id,
    ];
    $response = $this->json('POST', '/api/v1/bookings', $data, ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'message' => true,
        'data' => true,
      ]);

    $response = $this->json('POST', '/api/v1/bookings', $data, ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(422)
      ->assertJson([
        'error'   => 'already_booked',
      ]);
  }

  /**
   * Test POST /api/v1/bookings
   * User has active booking
   *
   * @return void
   */
  public function testStoreUserHasActiveBooking()
  {
    $user  = User::has('bookings')->first();
    $token = JWTAuth::fromUser($user);

    // Find an offer which has vacancies and does not belong to $user
    $offer = Offer::doesntHave('bookings')->where('user_id', '!=', $user->id)->first();
    $data = [
      'offer_id' => $offer->id,
    ];
    $response = $this->json('POST', '/api/v1/bookings', $data, ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(422)
      ->assertJson([
        'error'   => 'active_booking_exists',
      ]);
  }
}

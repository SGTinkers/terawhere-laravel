<?php

namespace Tests\Feature;

use App\Booking;
use App\Offer;
use App\User;
use Carbon\Carbon;
use DB;
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
   * @group Booking
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
   * @group Booking
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
   * @group Booking
   * @return void
   */
  public function testShow()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);
    $booking = Booking::first();

    $response = $this->json('GET', '/api/v1/bookings/' . $booking->id, [], ['Authorization' => 'Bearer ' . $token]);

    $data = $booking->toArray();
    $data['name']   = $booking->user->name;
    $data['gender'] = $booking->user->gender;

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
   * @group Booking
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
   * @group Booking
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
   * @group Booking
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
   * @group Booking
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
   * @group Booking
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
    $offersAndBookings = Offer::select([DB::raw('count(offers.id) as bookings'), 'offers.id as id'])->join('bookings', 'bookings.offer_id', '=', 'offers.id')->groupBy('offers.id');
    $offer = Offer::select('offers.*')->leftJoin(DB::raw('(' . $offersAndBookings->toSql() . ') as meta'), 'meta.id', '=', 'offers.id')->where('offers.user_id', '!=', $user->id)->whereRaw(DB::raw('IFNULL(meta.bookings, 0) = offers.vacancy'))->first();
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
   * @group Booking
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
   * @group Booking
   * @return void
   */
  public function testStoreUserHasActiveBooking()
  {
    $user  = Booking::active()->first()->user;
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

  /**
   * Test DELETE /api/v1/bookings/{id}
   * Successful case
   *
   * @group Booking
   * @return void
   */
  public function testDelete()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);
    $booking = Booking::where('user_id', $user->id)->first();

    $response = $this->json('DELETE', '/api/v1/bookings/' . $booking->id, [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'data'  => true,
        'message' => true,
      ]);
  }

  /**
   * Test DELETE /api/v1/bookings/{id}
   * Failed case: Not found
   *
   * @group Booking
   * @return void
   */
  public function testDeleteNotFound()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);

    $response = $this->json('DELETE', '/api/v1/bookings/0', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(404)
      ->assertJson([
        'error'   => 'booking_not_found',
      ]);
  }

  /**
   * Test DELETE /api/v1/bookings/{id}
   * Failed case: Not the owner
   *
   * @group Booking
   * @return void
   */
  public function testDeleteNotOwner()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);
    $booking = Booking::where('user_id', '!=', $user->id)->first();

    $response = $this->json('DELETE', '/api/v1/bookings/' . $booking->id, [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(403)
      ->assertJson([
        'error'   => 'forbidden_request',
      ]);
  }

  /**
   * Test GET /api/v1/offers/{id}/bookings
   * Success case
   *
   * @group Booking
   * @return void
   */
  public function testGetOffersBookings()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);
    $offer = Offer::first();

    $response = $this->json('GET', '/api/v1/offers/' . $offer->id . '/bookings', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'data'   => $offer->bookings->toArray(),
      ]);
  }

  /**
   * Test GET /api/v1/offers/{id}/bookings
   * Not found
   *
   * @group Booking
   * @return void
   */
  public function testGetOffersBookingsNotFound()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);

    $response = $this->json('GET', '/api/v1/offers/0/bookings', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(404)
      ->assertJson([
        'error'   => 'offer_not_found',
      ]);
  }

  /**
   * Test GET /api/v1/offers/{id}/bookings
   * Success: Empty array
   *
   * @group Booking
   * @return void
   */
  public function testGetOffersBookingsEmptyArray()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);
    $offer = Offer::doesntHave('bookings')->first();

    $response = $this->json('GET', '/api/v1/offers/' . $offer->id . '/bookings', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertExactJson([
        'data'   => [],
      ]);
  }

  /**
   * Test GET /users/bookings
   * Success case
   *
   * @group Booking
   * @return void
   */
  public function testGetUsersBookings()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);

    $response = $this->json('GET', '/api/v1/users/bookings', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'data'   => Booking::withTrashed()->where('user_id', $user->id)->get()->toArray(),
      ]);
  }

  /**
   * Test GET /api/v1/users/bookings
   * Success: Empty array
   *
   * @group Booking
   * @return void
   */
  public function testGetUsersBookingsEmptyArray()
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

    $response = $this->json('GET', '/api/v1/users/bookings', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertExactJson([
        'data'   => [],
      ]);
  }
}

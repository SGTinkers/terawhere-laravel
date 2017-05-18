<?php

namespace Tests\Feature;

use App\Offer;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use JWTAuth;

class OfferTest extends TestCase
{

  /**
   * Test GET /api/v1/offers
   * Return with results
   *
   * @return void
   */
  public function testIndex()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);

    $response = $this->json('GET', '/api/v1/offers', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'data'  => true,
      ]);
  }
  /**
   * Test GET /api/v1/offers
   * Return no results
   *
   * @return void
   */
  public function testIndexNoResults()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);

    Offer::where('id', 'like', '%%')->delete();

    $response = $this->json('GET', '/api/v1/offers', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertExactJson([
        'data'  => [],
      ]);
  }
  /**
   * Test GET /api/v1/offers/{offer}
   * Return selected offer
   *
   * @return void
   */
  public function testShow(){
  	$user  = User::first();
    $token = JWTAuth::fromUser($user);
    $offer = Offer::first();

    $response = $this->json('GET', '/api/v1/offers/'. $offer->id, [], ['Authorization' => 'Bearer ' . $token]);

    $data = $offer->toArray();
    $data['name']   = $user->name;
    $data['gender'] = $user->gender;

    $response
      ->assertStatus(200)
      ->assertExactJson([
        'data'  => $data,
      ]);
  }
  /**
   * Test GET /api/v1/offers/{offer}
   * Return 404, offer not found.
   *
   * @return void
   */
  public function testShowNotFound(){
  	$user  = User::first();
    $token = JWTAuth::fromUser($user);

    Offer::find(1)->delete();

    $response = $this->json('GET', '/api/v1/offers/1', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(404)
      ->assertExactJson([
        'error'   => 'offer_not_found',
        'message' => 'Offer does not exist',
      ]);
  }

  /**
   * Test POST /api/v1/offers with nullables filled
   * Return with results.
   *
   * @return void
   */
  public function testStoreWithNullables(){
  	$user  = User::first();
    $token = JWTAuth::fromUser($user);

    $response = $this->json('POST', '/api/v1/offers/1', [

      'meetup_time'    => '2012-12-21 23:59',
      'start_name'     => 'foo',
      'start_addr'     => 'bar',
      'start_lat'      => 89,
      'start_lng'      => 179,
      'end_name'       => 'hello',
      'end_addr'       => 'world',
      'end_lat'        => -89,
      'end_lng'        => -179,
      'vacancy'        => 1,
      'remarks'        => 'Hello',
      'pref_gender'    => $user->gender,
      'vehicle_number' => 'ABC123X',
      'vehicle_desc'   => 'Yellow',
      'vehicle_model'  => 'Submarine',

      ], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertExactJson([
        'message' => 'Offer added successfully.',
      	'data'    => 
	      [
	      'meetup_time'    => '2012-12-21 23:59',
	      'start_name'     => 'foo',
	      'start_addr'     => 'bar',
	      'start_lat'      => 89,
	      'start_lng'      => 179,
	      'end_name'       => 'hello',
	      'end_addr'       => 'world',
	      'end_lat'        => -89,
	      'end_lng'        => -179,
	      'vacancy'        => 1,
	      'remarks'        => 'Hello',
	      'pref_gender'    => $user->gender,
	      'vehicle_number' => 'ABC123X',
	      'vehicle_desc'   => 'Yellow',
	      'vehicle_model'  => 'Submarine',
	      ],
      ]);
  	}
  /**
   * Test POST /api/v1/offers without nullables filled.
   * Return with results.
   *
   * @return void
   */
  public function testStoreWithNullables(){
  	$user  = User::first();
    $token = JWTAuth::fromUser($user);

    $response = $this->json('POST', '/api/v1/offers/1', [

      'meetup_time'    => '2012-12-21 23:59',
      'start_name'     => 'foo',
      'start_addr'     => 'bar',
      'start_lat'      => 89,
      'start_lng'      => 179,
      'end_name'       => 'hello',
      'end_addr'       => 'world',
      'end_lat'        => -89,
      'end_lng'        => -179,
      'vacancy'        => 1,
      'vehicle_number' => 'ABC123X',
      'vehicle_model'  => 'Submarine',

      ], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertExactJson([
        'message' => 'Offer added successfully.',
      	'data'    => 
	      [
	      'meetup_time'    => '2012-12-21 23:59',
	      'start_name'     => 'foo',
	      'start_addr'     => 'bar',
	      'start_lat'      => 89,
	      'start_lng'      => 179,
	      'end_name'       => 'hello',
	      'end_addr'       => 'world',
	      'end_lat'        => -89,
	      'end_lng'        => -179,
	      'vacancy'        => 1,
	      'remarks'        => null,
	      'pref_gender'    => null,
	      'vehicle_number' => 'ABC123X',
	      'vehicle_desc'   => null,
	      'vehicle_model'  => 'Submarine',
	      ],
      ]);
  }  	    
}

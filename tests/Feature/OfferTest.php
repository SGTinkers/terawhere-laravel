<?php

namespace Tests\Feature;

use App\Offer;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use JWTAuth;
use Latrell\Geohash\Facades\Geohash;
use Carbon\Carbon;

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
        'data'  => Offer::all()->toArray(),
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

    $start_geohash =	Geohash::encode(89,179);
    $end_geohash   =	Geohash::encode(-89,-179);

    $response = $this->json('POST', '/api/v1/offers', [

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
      ->seeJson([
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
	      'start_geohash'  => $start_geohash,
	      'end_geohash'    => $end_geohash,
	      ],
      ]);
  	}


  /**
   * Test POST /api/v1/offers without nullables filled.
   * Return with results.
   *
   * @return void
   */
  public function testStoreWithoutNullables(){
  	$user  = User::first();
    $token = JWTAuth::fromUser($user);

    $start_geohash =	Geohash::encode(89,179);
    $end_geohash   =	Geohash::encode(-89,-179);

    $response = $this->json('POST', '/api/v1/offers', [

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
      ->seeJson([
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
	      'start_geohash'  => $start_geohash,
	      'end_geohash'    => $end_geohash,
	      ],
      ]);
  }


  /**
   * Test POST /api/v1/offers sending nothing.
   * Return with error messages.
   *
   * @return void
   */  
  public function testStoreEmpty(){
  	$user  = User::first();
    $token = JWTAuth::fromUser($user);

    $response = $this->json('POST', '/api/v1/offers', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(422)
      ->assertExactJson(
      	[
			  'meetup_time' => [
			    'The meetup time field is required.'
			  ],
			  'start_name' => [
			    'The start name field is required.'
			  ],
			  'start_addr' => [
			    'The start addr field is required.'
			  ],
			  'start_lat' => [
			    'The start lat field is required.'
			  ],
			  'start_lng' => [
			    'The start lng field is required.'
			  ],
			  'end_name' => [
			    'The end name field is required.'
			  ],
			  'end_addr' => [
			    'The end addr field is required.'
			  ],
			  'end_lat' => [
			    'The end lat field is required.'
			  ],
			  'end_lng' => [
			    'The end lng field is required.'
			  ],
			  'vacancy' => [
			    'The vacancy field is required.'
			  ],
			  'vehicle_number' => [
			    'The vehicle number field is required.'
			  ],
			  'vehicle_model' => [
			    'The vehicle model field is required.'
			  ]
      ]);
  }  	   
  /**
   * Test PUT /api/v1/offers/{offer}
   * Return with results.
   *
   * @return void
   */  
  public function testUpdate(){
  	$user  = User::first();
    $token = JWTAuth::fromUser($user);
    $offer = $user->offers()->first();
    
    $start_geohash =	Geohash::encode(-89,-179);
    $end_geohash   =	Geohash::encode(89,179);

    $response = $this->json('PUT', '/api/v1/offers/'. $offer->id, [

		      'meetup_time'    => '2020-10-10 12:34',
		      'start_name'     => 'bar',
		      'start_addr'     => 'foo',
		      'start_lat'      => -89,
		      'start_lng'      => -179,
		      'end_name'       => 'hello',
		      'end_addr'       => 'world',
		      'end_lat'        => 89,
		      'end_lng'        => 179,
		      'vacancy'        => 2,
		      'remarks'        => 'World',
		      'pref_gender'    => $user->gender,
		      'vehicle_number' => 'DEF321Y',
		      'vehicle_desc'   => 'Blue',
		      'vehicle_model'  => 'Rocket',

    	], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->seeJson([
        'message' => 'Offer updated successfully.',
      	'data'    => 
	      [
		      'meetup_time'    => '2020-10-10 12:34',
		      'start_name'     => 'bar',
		      'start_addr'     => 'foo',
		      'start_lat'      => -89,
		      'start_lng'      => -179,
		      'end_name'       => 'hello',
		      'end_addr'       => 'world',
		      'end_lat'        => 89,
		      'end_lng'        => 179,
		      'vacancy'        => 2,
		      'remarks'        => 'World',
		      'pref_gender'    => $user->gender,
		      'vehicle_number' => 'DEF321Y',
		      'vehicle_desc'   => 'Blue',
		      'vehicle_model'  => 'Rocket',
	      	  'start_geohash'  => $start_geohash,
	      	  'end_geohash'    => $end_geohash,
	      ],
      ]);
  }
  /**
   * Test PUT /api/v1/offers/{offer}
   * Return with results.
   *
   * @return void
   */  
  public function testDestroy(){
  	$offer = Offer::first();
	$user  = $offer->user();
    $token = JWTAuth::fromUser($user);

    $response = $this->json('DELETE', '/api/v1/offers'.$offer->id, [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertJson([
			'message' => 'Offer deleted successfully.',
			'data'    => $offer,
      ]);
  }
}

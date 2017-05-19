<?php

namespace Tests\Feature;

use App\Device;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use JWTAuth;
use Tests\TestCase;

class DeviceTest extends TestCase
{
  use DatabaseTransactions;

  /**
   * Test POST /api/v1/devices
   * Success
   *
   * @group Device
   * @return void
   */
  public function testStore()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);

    $data = [
      'device_token' => 'testtoken',
      'platform' => 'android',
    ];

    $response = $this->json('POST', '/api/v1/devices', $data, ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200);
  }

  /**
   * Test POST /api/v1/devices
   * Wrong platform
   *
   * @group Device
   * @return void
   */
  public function testStoreWrongPlatform()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);

    $data = [
      'device_token' => 'testtoken',
      'platform' => 'test',
    ];

    $response = $this->json('POST', '/api/v1/devices', $data, ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(422);
  }

  /**
   * Test DELETE /api/v1/devices/{id}
   * Success
   *
   * @group Device
   * @return void
   */
  public function testDelete()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);
    $device = Device::create([
      'user_id' => $user->id,
      'device_token' => 'testtoken',
      'platform' => 'android',
    ]);

    $response = $this->json('DELETE', '/api/v1/devices/' . $device->id, [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200);
  }

  /**
   * Test DELETE /api/v1/devices/{id}
   * Fail: Not Exists
   *
   * @group Device
   * @return void
   */
  public function testDeleteNotExists()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);

    $response = $this->json('DELETE', '/api/v1/devices/0', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(404)
      ->assertJson([
        'error'   => 'resource_not_found',
      ]);
  }

  /**
   * Test DELETE /api/v1/devices/{id}
   * Fail: Not Owner
   *
   * @group Device
   * @return void
   */
  public function testDeleteNotOwner()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);
    $device = Device::create([
      'user_id' => User::where('id', '!=', $user->id)->first()->id,
      'device_token' => 'testtoken',
      'platform' => 'android',
    ]);

    $response = $this->json('DELETE', '/api/v1/devices/' . $device->id, [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(403)
      ->assertJson([
        'error'   => 'forbidden_request',
      ]);
  }

  /**
   * Test GET /api/v1/users/me/devices
   * Success
   *
   * @group Device
   * @return void
   */
  public function testGetUsersDevices()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);
    $device = Device::create([
      'user_id' => $user->id,
      'device_token' => 'testtoken',
      'platform' => 'android',
    ]);

    $response = $this->json('GET', '/api/v1/users/me/devices', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'data'   => [
          $device->toArray(),
        ],
      ]);
  }

  /**
   * Test GET /api/v1/users/me/devices
   * Success: Empty
   *
   * @group Device
   * @return void
   */
  public function testGetUsersDevicesEmptyArray()
  {
    $user  = User::first();
    $token = JWTAuth::fromUser($user);

    $response = $this->json('GET', '/api/v1/users/me/devices', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'data'   => [],
      ]);
  }
}

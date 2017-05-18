<?php

namespace Tests\Feature;

use App\User;
use JWTAuth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    /**
     * Test Facebook Login
     * Exchanges facebook token to our servers JWT Token.
     *
     * @return void
     */
    public function testAuthFacebook()
    {
      if (!env('FB_TOKEN')) {
        $this->assertTrue(true);
        return;
      }

      $response = $this->json('POST', '/api/v1/auth', ['token' => env('FB_TOKEN'), 'service' => 'facebook']);

      $response
        ->assertStatus(200)
        ->assertJson([
          'token' => true,
          'user' => true,
        ]);
    }

  /**
   * Test /api/v1/auth/refresh
   *
   * @return void
   */
    public function testRefresh()
    {
      $user = User::first();
      $token = JWTAuth::fromUser($user);

      $response = $this->json('GET', '/api/v1/auth/refresh', [], ['Authorization' => 'Bearer ' . $token]);

      $response
        ->assertStatus(200)
        ->assertHeader('Authorization')
        ->assertExactJson([
          'status' => 'Ok',
          'message' => 'Check Authorization Header for new token',
        ]);
    }

  /**
   * Test /api/v1/me
   *
   * @return void
   */
  public function testGetAuthenticatedUser()
  {
    $user = User::first();
    $token = JWTAuth::fromUser($user);

    $response = $this->json('GET', '/api/v1/me', [], ['Authorization' => 'Bearer ' . $token]);

    $response
      ->assertStatus(200)
      ->assertJson([
        'user' => true,
      ]);
  }
}

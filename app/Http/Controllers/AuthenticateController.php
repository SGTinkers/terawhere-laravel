<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authenticate;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateController extends Controller
{

  public function __construct()
  {
    $this->middleware('jwt.auth', ['except' => ['authenticate']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return "Auth index";
  }

  public function authenticate(Authenticate $request)
  {
    $user = null;
    $token = null;

    try {
      // verify the credentials and create a token for the user
      $socialUser = Socialite::driver($request->get('service'))->stateless()->userFromToken($request->get('token'));
      if (!$socialUser) {
        return response()->json(['error' => 'invalid_credentials'], 401);
      }
      $user = User::whereEmail($socialUser->getEmail())->first();
      if (!$user) {
        $user = new User;
        $user->email = $socialUser->getEmail();
        $user->name = $socialUser->getName();
        $user->password = Hash::make($request->get('token'));
        $user->saveOrFail();
      }
      $token = JWTAuth::fromUser($user);
    } catch (JWTException $e) {
      // something went wrong
      return response()->json(['error' => 'could_not_create_token'], 500);
    }

    // if no errors are encountered we can return a JWT
    return response()->json(compact('token', 'user'));
  }

  public function getAuthenticatedUser()
  {
    try {

      if (!$user = JWTAuth::parseToken()->authenticate()) {
        return response()->json(['user_not_found'], 404);
      }

    } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

      return response()->json(['token_expired'], $e->getStatusCode());

    } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

      return response()->json(['token_invalid'], $e->getStatusCode());

    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

      return response()->json(['token_absent'], $e->getStatusCode());

    }

    // the token is valid and we have found the user via the sub claim
    return response()->json(compact('user'));
  }
}

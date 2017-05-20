<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authenticate;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 * @resource Authentication
 *
 * The process flow is as such:
 *
 * 1. Mobile App login with Facebook or Google**
 * 2. Mobile App get the token (from Fb/G)
 * 3. Send token to server via `/api/v1/auth` endpoint
 * 4. Then server checks if we already have the user in local db:
 *
 *  a. If already in, return an auth token
 *
 *  b. Else, create user, then return an auth token
 *
 * The auth token is actually JWT token. Basically, to call an authorised endpoint, include the JWT token in the request header: `Authorization: Bearer [JWTTokenHere]`. The request will pass through if the token is valid. The user can also be identified with the token.
 *
 * The token does expire. If server returns `token_expired`, call `/api/v1/auth/refresh` to get a new token. The token is returned in `Authorization` header.
 *
 * To get the current logged in user based on the token, call `/api/v1/me`.
 *
 * ** Fb uses same client_id/secret, Google might be different. For example, for Android: https://developers.google.com/identity/sign-in/android/start-integrating
 */
class AuthenticateController extends Controller
{
  public function __construct()
  {
    $this->middleware('jwt.refresh')->only('refresh');
  }

  /**
   * Authenticate User
   *
   * Exchanges social network token to JWT bearer token.
   *
   */
  public function auth(Authenticate $request)
  {
    $user  = null;
    $token = null;

    try {
      // verify the credentials
      $socialUser = Socialite::driver($request->get('service'))->stateless()->userFromToken($request->get('token'));
      if (!$socialUser) {
        return response()->json(['error' => 'invalid_credentials', 'message' => 'Invalid credentials'], 401);
      }

      // check if we already have user in db
      $user = User::where($request->get('service') . '_id', $socialUser->getId())->first();
      if (!$user) {
        // create user if we don't and fill some data from social network
        $user           = new User;
        $user->email    = $socialUser->getEmail();
        $user->name     = $socialUser->getName();
        $user->password = Hash::make($request->get('token'));
        $user->dp       = $socialUser->getAvatar();

        // fill in gender if we have
        if ($socialUser->offsetGet('gender') && ($socialUser->offsetGet('gender') === "male" || $socialUser->offsetGet('gender') === "female")) {
          $user->gender = $socialUser->offsetGet('gender');
        }

        // fill in the relevant ids
        if ($request->get('service') === "google") {
          $user->google_id = $socialUser->getId();
        } else if ($request->get('service') === "facebook") {
          $user->facebook_id = $socialUser->getId();
        }

        $user->saveOrFail();
      } else {
        // update email if email field is empty
        if (!$user->email) {
          $user->email = $socialUser->getEmail();
        }

        // update dp if dp field is empty
        if (!$user->dp) {
          $user->dp = $socialUser->getAvatar();
        }

        // update gender if gender field is empty
        if (!$user->gender) {
          if ($socialUser->offsetGet('gender') && ($socialUser->offsetGet('gender') === "male" || $socialUser->offsetGet('gender') === "female")) {
            $user->gender = $socialUser->offsetGet('gender');
          }
        }

        // fill in relevant ids
        if ($request->get('service') === "google") {
          $user->google_id = $socialUser->getId();
        } else if ($request->get('service') === "facebook") {
          $user->facebook_id = $socialUser->getId();
        }

        $user->saveOrFail();
      }

      // generate JWT token
      $token = JWTAuth::fromUser($user);
    } catch (JWTException $e) {
      // something went wrong
      return response()->json(['error' => 'could_not_create_token', 'message' => 'Could not create token.'], 500);
    }

    // if no errors are encountered we can return a JWT
    return response()->json(compact('token', 'user'));
  }

  /**
   * Refresh Token
   *
   * * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   * Check Authorization header for new token.
   * Call this API to exchange expired (not invalid!) JWT token with a fresh one.
   *
   */
  public function refresh()
  {
    return response()->json(['status' => 'Ok', 'message' => 'Check Authorization Header for new token']);
  }

  /**
   * Get Authenticated User
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Retrieves the user associated with the JWT token.
   *
   */
  public function getAuthenticatedUser()
  {
    $user = Auth::user();

    $roles = [];
    foreach($user->roles as $role){
        $roles[] = $role->role;
    }
    $user['role'] = $roles;
    // the token is valid and we have found the user via the sub claim
    return response()->json([
      'user' => $user,
  ], 200);
  }
}

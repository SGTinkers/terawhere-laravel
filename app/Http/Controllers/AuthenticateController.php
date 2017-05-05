<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authenticate;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateController extends Controller
{
  /**
   * Instantiate a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('jwt.refresh')->only('refresh');
  }

  public function auth(Authenticate $request)
  {
    $user  = null;
    $token = null;

    try {
      // verify the credentials
      $socialUser = Socialite::driver($request->get('service'))->stateless()->userFromToken($request->get('token'));
      if (!$socialUser) {
        return response()->json(['error' => 'invalid_credentials'], 401);
      }

      // check if we already have user in db
      $user = User::whereEmail($socialUser->getEmail())->first();
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
      return response()->json(['error' => 'could_not_create_token'], 500);
    }

    // if no errors are encountered we can return a JWT
    return response()->json(compact('token', 'user', 'socialUser'));
  }

  public function refresh()
  {
    return response()->json(["status" => "ok"]);
  }

  public function getAuthenticatedUser()
  {
    $user = Auth::user();

    // the token is valid and we have found the user via the sub claim
    return response()->json(compact('user'));
  }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetUserId;
use App\User;
use Illuminate\Http\Request;

/**
 * @resource User
 *
 * Only admins
 */
class UserController extends Controller
{
  /**
   * Display a listing of all users
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      $users = User::all();
      return response()->json([
          'data' => $users,
      ], 200);
  }

   /**
   * Display all info about selected user
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $user = User::find($id);
      if (!$user) {
          return response()->json([
              'error'   => 'user_not_found',
              'message' => 'User does not exist',
          ], 404);
      }
      $roles = [];
      foreach ($user->roles as $role) {
          $roles[] = $role->role;
      }
      $user['role'] = $roles;
      $user['devices'] = $user->devices;
      $user['offers'] = $user->offers;
      $user['bookings'] = $user->bookings;

    return response()->json([
          'data' => $user,
    ], 200);
  }


  /**
   * Ban a user
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function ban(GetUserId $request)
  {
    $user = User::find($request->user_id);
      if (!$user) {
          return response()->json([
              'error'   => 'user_not_found',
              'message' => 'User does not exist',
          ], 404);
      }

      return response()->json([
          'message'=> 'User successfully banned until (DEV NOTICE: not implemented yet)',
          'data' => $user,
      ], 200);

  }

}

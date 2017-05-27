<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetUserId;
use Illuminate\Http\Request;
use App\Http\Requests\GetUserRole;
use App\Role;

/**
 * @resource Role
 *
 * All methods relating to User Roles
 *
 */
class RoleController extends Controller
{
    /**
     * Display a listing of all roles
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return response()->json([
            'data' => $roles,
        ], 200);
    }

    /**
     * Add a role to user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GetUserRole $request)
    {
        $data = $request->all();
        $role = Role::create($data);
        return response()->json([
            'message' => $request->role.' role added to user, '.$role->user->name.', successfully.',
            'data'    => $role,
        ], 200);
    }

    /**
     * Remove a role from user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GetUserRole $request)
    {
        $role = Report::where('role', $request->role)->where('user_id', $request->user_id)->first();
        if(!$role){
            return response()->json([
                'error'   => 'resource_not_found',
                'message' => 'User role does not exist',
            ], 404);
        }
        Role::where('role', $request->role)->where('user_id', $request->user_id)->delete();
        return response()->json([
            'message' => $request->role.' role removed from user successfully.',
            'data'    => $role,
        ], 200);
    }

    /**
     * Display a user's roles
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getUsersRoles(GetUserId $request)
    {
        //if user_id not passed
        if (!isset($request->user_id) || empty($request->user_id)) {
            $user_id = Auth::user()->id; //set user id to current user
        } else {
            $user_id = $request->user_id;
        }

        $roles = Role::where('user_id',$user_id)->get();
        return response()->json([
            'data' => $roles,
        ], 200);
    }
}

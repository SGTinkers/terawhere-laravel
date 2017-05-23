<?php

namespace App\Http\Controllers;

use App\Device;
use App\Http\Requests\GetDeviceToken;
use App\Notifications\TestNotification;
use App\User;
use Illuminate\Support\Facades\Auth;

/**
 * @resource Notification
 *
 * Push notifications stuff with FCM is handled here.
 */

class DeviceController extends Controller
{
  /**
   * Store device token
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   */
  public function store(GetDeviceToken $request)
  {
    $data            = $request->all();
    $data["user_id"] = Auth::user()->id;

    $deviceToken = Device::where('device_token', $data['device_token'])->first();

    if ($deviceToken) {
      return response()->json([
        'message' => 'Device token already registered.',
        'data'    => $deviceToken,
      ], 200);
    }

    $device = Device::create($data);
    return response()->json([
      'message' => 'Device token added successfully.',
      'data'    => $device,
    ], 200);
  }

  /**
   * Delete device token
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   */
  public function destroy($id)
  {
    $device = Device::find($id);
    if (!$device) {
      return response()->json([
        'error'   => 'resource_not_found',
        'message' => 'Device does not exist.',
      ], 404);
    }

    if ($device->user_id != Auth::user()->id) {
      return response()->json([
        'error'   => 'forbidden_request',
        'message' => 'User does not have permission to delete this device.',
      ], 403);
    }

    $device->delete(); //device is soft deleted.
    return response()->json([
      'message' => 'Device deleted successfully.',
      'data'    => $device,
    ]);

  }

  /**
   * Get devices belonging to a user
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   */
  public function getUsersDevices()
  {
    return response()->json([
      'data' => Auth::user()->devices,
    ], 200);
  }

  /**
   * Send test notification
   *
   */
  public function sendTestNotification($user_id)
  {
    $user = User::findOrFail($user_id);
    $user->notify(new TestNotification($user));
  }
}

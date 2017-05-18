<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\User;
use App\Device;
use App\Http\Requests\GetDeviceToken;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

use App\Notifications\TestNotification;

/**
 * @resource Notification
 *
 * Push notifications stuff with FCM is handled here.
 */

class NotificationController extends Controller
{
  /**
   * Store device token
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   */
    public function store(GetDeviceToken $request){
    	$data =	$request->all();
	    $data["user_id"] = Auth::user()->id;

      $devicetoken = Device::where('device_token', $data['device_token'])->first();

      if($devicetoken){
        return response()->json([
          'message' => 'Device token already exists.',
          'data'    => $devicetoken,
        ], 422);
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
        'error' => 'Resource_not_found',
        'message' => 'Device does not exist.'
        ], 404);
    }
    
    if($device->user_id != Auth::user()->id){
      return response()->json([
        'error' => 'Forbidden_request',
        'message' => 'User does not have permission to delete this device.'
        ], 403);
    }
    
    $device->delete();     //device is soft deleted.
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
  		$devices = Auth::user()->devices();

      if ($devices->isEmpty()) {
      return response()->json([
        'error' => 'Resource_not_found',
        'message' => 'There are no devices for this user.'
        ], 404);
      }
      return response()->json([
      'data' => $devices,
    ], 200);
  	}
    /**
   * Send test notification
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   */
    public function sendTestNotification() {
    	Auth::user()->notify(new TestNotification(Auth::user()));
    }
}

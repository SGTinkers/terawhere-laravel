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
    public function storeDeviceToken(GetDeviceToken $request){
    	$data =	$request->all();
	    $data["user_id"] = Auth::user()->id;

    	$device = Device::create($data);
    	return response()->json([
      	'message' => 'Device token added successfully.',
    	'data'    => $device,
    	], 200);
    }
    /**
   * Send test notification
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   */
    public function sendTestNotification() {
    	Auth::user()->notify(new TestNotification());
    }
}

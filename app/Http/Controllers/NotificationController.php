<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\User;
use App\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class NotificationController extends Controller
{
    public function storeDeviceToken(Request $request){
    	$data =	$request->all();
	    $data["user_id"] = Auth::user()->id;

    	$device = Device::create($data);

    }
}

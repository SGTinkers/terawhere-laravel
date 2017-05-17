<?php

namespace App\Notifications\Channels;

use App\Device;
use FCM;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

/**
 * Class FcmChannel
 * @package App\Notifications\Channels
 */
class FcmChannel
{
  public function __construct()
  {
    //
  }

  /**
   * @param mixed $notifiable
   * @param Notification $notification
   */
  public function send($notifiable, Notification $notification)
  {
    list($user, $notification, $data, $option) = $notification->toFCM($notifiable);

    Log::info("Notification: " . $notification->title);
    foreach ($user->devicesTokens() as $token) {
      Log::info('Sending notification to: ' . $token);
    }

    $response = FCM::sendTo($user->devicesTokens(), $option, $notification, $data);

    //return Array - you must remove all this tokens in your database
    foreach ($response->tokensToDelete() as $token) {
      Device::where('device_token', $token)->first()->forceDelete();
    }

    //return Array (key : oldToken, value : new token - you must change the token in your database )
    foreach ($response->tokensToModify() as $tokenOld => $tokenNew) {
      $device = Device::where('device_token', $tokenOld)->first();
      $device->device_token = $tokenNew;
      $device->save();
    }

    // TODO: Handle this
    //return Array - you should try to resend the message to the tokens in the array
    // $downstreamResponse->tokensToRetry();

    // return Array (key:token, value:errror) - in production you should remove from your database the tokens present in this array
    foreach ($response->tokensWithError() as $token) {
      Device::where('device_token', $token)->first()->forceDelete();
    }
  }
}
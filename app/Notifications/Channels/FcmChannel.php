<?php

namespace App\Notifications\Channels;

use App\Device;
use FCM;
use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

/**
 * Class FcmChannel
 * @package App\Notifications\Channels
 */
class FcmChannel
{
  /**
   * @const The API URL for Firebase
   */
  const API_URI = 'https://fcm.googleapis.com/fcm/send';

  /**
   * @var Client
   */
  private $client;

  /**
   * @param Client $client
   */
  public function __construct(Client $client)
  {
    $this->client = $client;
  }

  /**
   * @param mixed $notifiable
   * @param Notification $notification
   */
  public function send($notifiable, Notification $notification)
  {
    list($user, $notification, $data, $option) = $notification->toFCM($notifiable);

    $response = FCM::sendTo($user->devicesTokens(), $option, $notification);

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
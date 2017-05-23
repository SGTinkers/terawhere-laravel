<?php

namespace App\Notifications;

use App\Notifications\Channels\FcmChannel;
use App\Offer;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class DriverUpdateOffer extends Notification
{
  use Queueable;

  /**
   * @var User
   */
  private $user;

  /**
   * @var Offer
   */
  private $offer;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct(User $user, Offer $offer)
  {
    $this->user = $user;
    $this->offer = $offer;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return [FcmChannel::class];
  }

  public function toFcm($notifiable)
  {
    $optionBuilder = new OptionsBuilder();
    $optionBuilder->setTimeToLive(60 * 60 * 24);

    $notificationBuilder = new PayloadNotificationBuilder('Driver updated ride details');
    $notificationBuilder->setBody($this->offer->user->name . ' just updated his ride details.')->setSound('default');

    $dataBuilder = new PayloadDataBuilder();
    $dataBuilder->addData(['offer' => $this->offer->toArray()]);

    $option       = $optionBuilder->build();
    $notification = $notificationBuilder->build();
    $data         = $dataBuilder->build();

    return [$this->user, $notification, $data, $option];
  }

  /**
   * Get the array representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function toArray($notifiable)
  {
    return [
      //
    ];
  }
}

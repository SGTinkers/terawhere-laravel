<?php

namespace App\Notifications;

use App\Booking;
use App\Notifications\Channels\FcmChannel;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class PassengerMadeBooking extends Notification
{
  use Queueable;

  /**
   * @var User
   */
  private $user;

  /**
   * @var Booking
   */
  private $booking;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct(User $user, Booking $booking)
  {
    $this->user = $user;
    $this->booking = $booking;
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

    $notificationBuilder = new PayloadNotificationBuilder('Passenger hitched!');
    $notificationBuilder->setBody($this->booking->user->name . ' just booked your ride.')->setSound('default');

    $dataBuilder = new PayloadDataBuilder();
    $dataBuilder->addData(['booking' => $this->booking->toArray()]);

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

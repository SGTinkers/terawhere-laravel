<?php

namespace App\Notifications;

use App\Notifications\Channels\FcmChannel;
use App\Booking;
use App\Offer;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class RemindPassengerMeetup extends Notification
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
     * @var Countdown
     */
    private $countdown;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Booking $booking, $passenger_countdown = 30)
    {
        $this->user = $user;
        $this->booking = $booking;
        $this->countdown = $passenger_countdown;
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

        $notificationBuilder = new PayloadNotificationBuilder('Your trip is starting soon!');
        $notificationBuilder->setBody('Hi ' . $this->user->name . ', your ride with ' . $this->booking->offer->user->name . " is in ". $this->countdown ." minutes, don't be late!")->setSound('default');

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

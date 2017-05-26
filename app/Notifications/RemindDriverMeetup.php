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

class RemindDriverMeetup extends Notification
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
     * @var Countdown
     */
    private $countdown;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Offer $offer, $driver_countdown = 10)
    {
        $this->user = $user;
        $this->offer = $offer;
        $this->countdown = $driver_countdown;
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

        $notificationBuilder = new PayloadNotificationBuilder('Your trip is approaching soon!');
        $notificationBuilder->setBody('Hi ' . $this->user->name . ', the ride you offered is in '. $this->countdown .' minutes!')->setSound('default');

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

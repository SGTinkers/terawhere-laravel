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

class BatchOfferReminder extends Notification
{
    use Queueable;

    /**
     * @var User
     */
    private $user;

    private $new_offers_count;

    private $total_active_offers_count;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $new_offers_count, $total_active_offers_count)
    {
        $this->user = $user;
        $this->total_active_offers_count   = $total_active_offers_count;
        $this->new_offers_count            = $new_offers_count ;
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

        $notificationBuilder = new PayloadNotificationBuilder('There are new offers!');
        $notificationBuilder->setBody('There are '. $this->new_offers_count .' new ride offer(s) available. There are now '. $this->total_active_offers_count  .' offer(s) in total!')->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([]);

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

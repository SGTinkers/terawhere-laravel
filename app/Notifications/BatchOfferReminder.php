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

    private $string_one;

    private $string_two;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $new_offers_count, $total_active_offers_count)
    {
        $this->user = $user;
        $this->total_active_offers_count   = $total_active_offers_count;
        $this->new_offers_count            = $new_offers_count;

        if($this->new_offers_count == 1){
            $this->string_one = 'There is one new ride offer posted.';
        }else{
            $this->string_one = 'There are '. $this->new_offers_count .' new ride offers posted.';
        }

        if($this->total_active_offers_count == 1){
            $this->string_two = 'Tap to book now!';
        }else{
            $this->string_two = 'There are now ' . $this->total_active_offers_count. ' offers available in total!';
        }
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
        $notificationBuilder->setBody($this->string_one.' '.$this->string_two)->setSound('default');

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

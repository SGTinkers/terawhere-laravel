<?php

namespace App\Console\Commands;

use App\Notifications\RemindDriverMeetup;
use App\Notifications\RemindPassengerMeetup;
use Illuminate\Console\Command;
use App\Offer;
use Carbon\Carbon;

class RemindUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RemindUser:reminduser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notification to remind users that their meetup time is approaching';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now    = Carbon::now();
        $offers = Offer::active()->get();
        $driver_countdown = 10; //how many minutes earlier to remind
        $passenger_countdown = 30;

        foreach($offers as $offer){
            //get meetup_time from for this offer
            $meetup_time = Carbon::createFromFormat('Y-m-d H:i:s', $offer->meetup_time);
            if($now >= $meetup_time->subMinutes($driver_countdown) && $offer->notified != 2){
                //send push notif
                $offer->user->notify(new RemindDriverMeetup($offer->user, $offer, $driver_countdown));
                //set notified to 2.
                $offer->notified = 2;
                $offer->save();
            }

            foreach($offer->bookings as $booking){
                if($now >= $meetup_time->subMinutes($passenger_countdown) && $booking->notified == 0){
                    //send push notification
                    $booking->user->notify(new RemindPassengerMeetup($booking->user, $booking, $passenger_countdown));

                    $booking->notified = 1;
                    $booking->save();
                }
            }
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Notifications\BatchOfferReminder;
use Illuminate\Console\Command;
use App\Offer;
use App\User;
use Carbon\Carbon;

class BatchNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BatchNotify:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifies all users of available number of rides.';

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
        //Aziz: To do: Only remind users based on location and whether they want to be reminded.
        $offers                      = Offer::active()->get();
        $new_offers_count            = count(Offer::active()->where('notified', 0)->get());
        $total_active_offers_count   = count($offers);

        $latest_offer                = Offer::orderBy('meetup_time', 'desc')->first();
        $latest_meetup_time          = Carbon::createFromFormat('Y-m-d H:i:s', $latest_offer->meetup_time);
        $now                         = Carbon::now();
        $diff                        = $now->diffInHours($latest_meetup_time);

        $users = User::all();
        if($new_offers_count >= 3 || $diff <= 2){
            foreach($users as $user){
                $user->notify(new BatchOfferReminder($user, $new_offers_count, $total_active_offers_count));
            }
            foreach($offers as $offer){
                $offer->notified = 1;
                $offer->save();
            }
        }
    }
}

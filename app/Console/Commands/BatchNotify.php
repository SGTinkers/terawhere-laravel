<?php

namespace App\Console\Commands;

use App\Notifications\BatchOfferReminder;
use Illuminate\Console\Command;
use App\Offer;
use App\User;

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
        $offers = Offer::active()->get();
        $total_active_offers_count   = count($offers);
        $new_offers_count    = 0;

        foreach($offers as $offer){
            if($offer->notified == 0) {
                $new_offers_count++;

                //set notified to 1.
                $offer->notified = 1;
                $offer->save();
            }
        }

        $users = User::all();
        foreach($users as $user)
        {
            if($new_offers_count != 0 && $total_active_offers_count != 0){
            $user->notify(new BatchOfferReminder($user, $new_offers_count, $total_active_offers_count));
            }
        }
    }
}

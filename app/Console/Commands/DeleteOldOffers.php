<?php

namespace App\Console\Commands;

use App\Offer;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteOldOffers extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'DeleteOldOffers:deleteoffers';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Delete Offers where meetup_time is older than now.';

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
    $now = Carbon::now();
    $offers = Offer::where('meetup_time', '<=', $now)->get();
    foreach($offers as $offer){
        $offer->status = Offer::STATUS['EXPIRED'];
        $offer->save();
    }
    Offer::where('meetup_time', '<=', $now)->delete();
  }
}

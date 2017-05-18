<?php

use App\Booking;
use App\Offer;
use App\User;
use Illuminate\Database\Seeder;

class BookingsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $users = User::get();
    foreach ($users as $user) {
      foreach (range(1, 5) as $index) {
        $offer = Offer::find(rand(1, 50));
        if (!$offer || $offer->user_id == $user->id) {
          continue;
        }

        Booking::create([
          'user_id'  => $user->id,
          'offer_id' => $offer->id,
          'pax'      => 1,
        ]);
      }
    }
  }

}

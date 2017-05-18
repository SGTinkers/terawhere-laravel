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
    $faker = Faker\Factory::create();

    $users = User::get();
    foreach ($users as $user) {
      foreach (range(1, 3) as $index) {
        $offer = Offer::find($faker->numberBetween($min = 1, $max = 30))->first();
        if ($offer->user_id == $user->id) {
          continue;
        }

        Booking::create([
          'user_id' => $user->id,
          'offer_id' => $offer->id,
          'pax' => 1,
        ]);
      }
    }
  }

}
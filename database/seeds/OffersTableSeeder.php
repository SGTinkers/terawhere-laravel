<?php

use App\Offer;
use App\User;
use Illuminate\Database\Seeder;
use Latrell\Geohash\Facades\Geohash;

class OffersTableSeeder extends Seeder
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
      foreach (range(1, 5) as $index) {
        $start_lat     = $faker->latitude($min = 1.2, $max = 1.45);
        $start_lng     = $faker->longitude($min = 103, $max = 105);
        $start_geohash = Geohash::encode($start_lat, $start_lng);
        $end_lat       = $faker->latitude($min = 1.2, $max = 1.45);
        $end_lng       = $faker->longitude($min = 103, $max = 105);
        $end_geohash   = Geohash::encode($end_lat, $end_lng);

        Offer::create([
          'user_id'        => $user->id,
          'meetup_time'    => $faker->dateTimeBetween($startDate = 'now', $endDate = '+1 days', $timezone = date_default_timezone_get()),
          'start_name'     => $faker->streetName,
          'start_addr'     => $faker->streetAddress,
          'start_lat'      => $start_lat,
          'start_lng'      => $start_lng,
          'start_geohash'  => $start_geohash,
          'end_name'       => $faker->streetName,
          'end_addr'       => $faker->streetAddress,
          'end_lat'        => $end_lat,
          'end_lng'        => $end_lng,
          'end_geohash'    => $end_geohash,
          'vacancy'        => $faker->numberBetween($min = 1, $max = 4),
          'vehicle_number' => 'S' . $faker->randomLetter . $faker->randomLetter . $faker->randomNumber(4) . $faker->randomLetter,
          'vehicle_model'  => $faker->firstName . ' ' . $faker->lastName,
        ]);
      }
    }
  }

}

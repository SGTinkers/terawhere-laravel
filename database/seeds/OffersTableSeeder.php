<?php

use App\Offer;
use Illuminate\Database\Seeder;

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

    foreach (range(1, 30) as $index) {
      Offer::create([
        'user_id'     => $faker->numberBetween($min = 1, $max = 5),
        'meetup_time' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+10 months', $timezone = date_default_timezone_get()),
        'start_name'  => $faker->streetName,
        'start_addr'  => $faker->streetAddress,
        'start_lat'   => $faker->latitude($min = -90, $max = 90),
        'start_lng'   => $faker->longitude($min = -180, $max = 180),
        'end_name'    => $faker->streetName,
        'end_addr'    => $faker->streetAddress,
        'end_lat'     => $faker->latitude($min = -90, $max = 90),
        'end_lng'     => $faker->longitude($min = -180, $max = 180),
      ]);
    }
  }
}

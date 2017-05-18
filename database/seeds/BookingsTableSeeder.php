<?php

use App\Booking;
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

    foreach (range(1, 30) as $index) {
      Booking::create([
        'user_id'     => UsersTableSeeder::$fake_users_inserted[$faker->numberBetween(11, 20) - 1],
        'offer_id'    => $faker->numberBetween($min = 1, $max = 30),
        'pax'         => 1,
      ]);
    }
  }

}
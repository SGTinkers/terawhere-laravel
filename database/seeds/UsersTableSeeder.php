<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
  public static $fake_users_inserted = [];

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = Faker\Factory::create();

    foreach (range(1, 20) as $index) {
      $user = User::create([
        'name'     => $faker->userName,
        'email'    => $faker->email,
        'password' => bcrypt('secret'),
      ]);

      UsersTableSeeder::$fake_users_inserted[] = $user->id;
    }
  }
}

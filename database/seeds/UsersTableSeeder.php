<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = Faker\Factory::create();

    foreach (range(1, 100) as $index) {
      $user = User::create([
        'name'     => $faker->userName,
        'email'    => $faker->email,
        'password' => bcrypt('secret'),
      ]);
    }
  }
}

<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * Run `composer du` first after editing seed file
   * Then, run `artisan db:seed` to seed
   * Else, run `artisan migrate:refresh --seed` to recreate tables and seed it
   *
   * @return void
   */
  public function run()
  {
    $this->call('UsersTableSeeder');
    $this->call('OffersTableSeeder');
    $this->call('BookingsTableSeeder');
  }
}

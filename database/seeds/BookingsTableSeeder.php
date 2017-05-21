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
    $users                              = User::get();
    $offer_id_with_full_bookings_filled = false;

    foreach ($users as $user) {
      if (!$offer_id_with_full_bookings_filled) {
        // Fill up the offer which should be fully booked first
        $offer = Offer::find(OffersTableSeeder::$offer_id_with_full_bookings);
        if ($offer->bookings->count() == $offer->vacancy - 1) {
          $offer_id_with_full_bookings_filled = true;
        }
      } else {
        // Find offer with vacancies
        $offersAndBookings = Offer::select([DB::raw('count(offers.id) as bookings'), 'offers.id as id'])->join('bookings', 'bookings.offer_id', '=', 'offers.id')->groupBy('offers.id');
        $offer             = Offer::select('offers.*')->leftJoin(DB::raw('(' . $offersAndBookings->toSql() . ') as meta'), 'meta.id', '=', 'offers.id')->where('offers.id', '!=', OffersTableSeeder::$offer_id_with_no_bookings)->where('offers.user_id', '!=', $user->id)->whereRaw(DB::raw('IFNULL(meta.bookings, 0) < offers.vacancy'))->first();
      }

      if (!$offer) {
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

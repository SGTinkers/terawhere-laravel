<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Http\Requests\GetDate;
use App\Http\Requests\GetNearby;
use App\Http\Requests\GetUserId;
use App\Http\Requests\StoreOffer;
use App\Notifications\DriverCancelOffer;
use App\Notifications\DriverUpdateOffer;
use App\Offer;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Latrell\Geohash\Facades\Geohash;

/**
 * @resource Offer
 *
 * All offers by drivers are handled here.
 */

class OfferController extends Controller
{
  /**
   * Get all offers
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Not recommended for use
   *
   * Returns ALL offers in database
   *
   */
  public function index(Request $request)
  {
    $offers = Offer::all();

    return response()->json([
      'data' => $offers,
    ], 200);
  }

  /**
   * Show a single offer
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Returns a single offer from offer_id
   *
   */
  public function show(Request $request, $id)
  {
    $offer = Offer::find($id);
    if (!$offer) {
      return response()->json([
        'error'   => 'offer_not_found',
        'message' => 'Offer does not exist',
      ], 404);
    }

    $user = User::find($offer->user_id);
    $totalpax = 0;
    foreach ($offer->bookings as $booking) {
      $totalpax = $totalpax + $booking->pax;
    }
    $offer['seats_booked']    = $totalpax;
    $offer['seats_remaining'] = $offer->vacancy - $totalpax;
    $offer['name']   = $user->name;
    $offer['gender'] = $user->gender;

    return response()->json([
      'data' => $offer,
    ], 200);
  }

  /**
   * Store an offer
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Returns Validation errors OR success message w/ data posted
   *
   */
  public function store(StoreOffer $request)
  {
    $data            = $request->all();
    $data["user_id"] = Auth::user()->id;

    $data['start_geohash'] = Geohash::encode($request->start_lat, $request->start_lng);
    $data['end_geohash']   = Geohash::encode($request->end_lat, $request->end_lng);

    if (isset($data['pref_gender']) && $data['pref_gender'] != Auth::user()->gender) {
      return response()->json([
        'error'   => 'invalid_request',
        'message' => 'Unable to select different prefered gender.',
      ], 422);
    }

    $now         = Carbon::now();
    $meetup_time = Carbon::createFromFormat('Y-m-d H:i:s', $data['meetup_time']);
    $limit       = Carbon::now()->addHours(24); //Use this if want 24 hour range instead of isToday();
    if ($meetup_time < $now || $meetup_time >= $limit) {
      return response()->json([
        'error'   => 'invalid_request',
        'message' => 'Unable to create an offer at that date/time.',
      ], 422);
    }

    $latestoffer = Offer::where('user_id', $data['user_id'])->orderBy('created_at', 'desc')->first();

    if ($latestoffer) { //if latest offer exists
      $now  = Carbon::now();
      $diff = Carbon::now()->diffInMinutes($latestoffer['created_at']);

      if ($diff < 10 && $latestoffer['start_addr'] == $data['start_addr'] && $latestoffer['end_addr'] == $data['end_addr']) {
        return response()->json([
          'error'   => 'invalid_request',
          'message' => 'User cannot add another similar offer too soon.',
        ], 422);
      }
    }

    $offer = Offer::create($data); //create Offer object, store in db

    return response()->json([
      'message' => 'Offer added successfully.',
      'data'    => $offer,
    ], 200);
  }
  /**
   * Update an offer
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Returns success message or 404.
   *
   */
  public function update(StoreOffer $request, $id)
  {
    $offer = Offer::find($id);

    if (!$offer) {
      return response()->json([
        'error'   => 'offer_not_found',
        'message' => 'Offer does not exist.',
      ], 404);
    }

    if ($offer->user_id != Auth::user()->id) {
      return response()->json([
        'error'   => 'forbidden_request',
        'message' => 'User does not have permission to edit this offer.',
      ], 403);
    }

    //prevent user from changing vacancy to be lower than number of passengers.
    $bookings = $offer->bookings;
    $totalpax = 0;
    foreach ($bookings as $booking) {
      $totalpax = $totalpax + $booking->pax;
    }

    if ($request->vacancy < $totalpax) {
      return response()->json([
        'error'   => 'invalid_request',
        'message' => 'User cannot change vacancy to be lower than number of passengers booked: ' . $totalpax,
      ], 422);
    }

    $meetup_time = Carbon::createFromFormat('Y-m-d H:i:s', $offer->meetup_time);
    $now         = Carbon::now();
    $diff        = Carbon::now()->diffInHours($meetup_time);
    $limit       = Carbon::now()->addHours(24); //Use this if want 24 hour range instead of isToday();

    if ($diff < 6) {
      return response()->json([
        'error'   => 'invalid_request',
        'message' => 'User cannot edit the offer 6 hours before meetup time.',
      ], 422);
    }

    if ($meetup_time < $now || $meetup_time >= $limit) {
      return response()->json([
        'error'   => 'invalid_request',
        'message' => 'Unable to update the offer at that date/time.',
      ], 422);
    }
    $offer->fill($request->all());
    $offer->save();

    // Push notification here to tell passengers that offer is updated.
    foreach ($offer->bookings as $booking) {
      $booking->user->notify(new DriverUpdateOffer($booking->user, $offer));
    }

    return response()->json([
      'message' => 'Offer updated successfully.',
      'data'    => $offer,
    ]);
  }

  /**
   * Cancel an offer
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Status: Cancelled by User= 0, Pending = 1, Ongoing = 2, Completed = 3, Expired by server = 4.
   *
   * Returns Success or 404.
   *
   */
  public function destroy($id)
  {
    $offer = Offer::find($id);
    if (!$offer) {
      return response()->json([
        'error'   => 'offer_not_found',
        'message' => 'Offer does not exist.',
      ], 404);
    }

    if ($offer->user_id != Auth::user()->id) {
      return response()->json([
        'error'   => 'forbidden_request',
        'message' => 'User does not have permission to delete this offer.',
      ], 403);
    }

    $offer->status = Offer::STATUS['CANCELLED'];
    $offer->save();

    // Aziz: To add push notif here to tell passengers that offer is cancelled.
    foreach ($offer->bookings as $booking) {
      $booking->user->notify(new DriverCancelOffer($booking->user, $offer));
    }

    Booking::where('offer_id', $id)->delete(); //delete bookings under that offer deleted as well.

    $offer->delete(); //offer is soft deleted.
    return response()->json([
      'message' => 'Offer deleted successfully.',
      'data'    => $offer,
    ]);

  }
    /**
     * Set an offer status to ongoing
     *
     * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
     *
     * Status: Cancelled by User= 0, Pending = 1, Ongoing = 2, Completed = 3, Expired by server = 4.
     *
     * Returns Success.
     *
     */
  public function setOngoing($id){
      $offer = Offer::find($id);
      if (!$offer) {
          return response()->json([
              'error'   => 'offer_not_found',
              'message' => 'Offer does not exist.',
          ], 404);
      }
      if ($offer->user_id != Auth::user()->id) {
          return response()->json([
              'error'   => 'forbidden_request',
              'message' => 'User does not have permission to edit this offer.',
          ], 403);
      }
      if ($offer->status != Offer::STATUS['PENDING']) { //can only change PENDING -> ONGOING
          return response()->json([
              'error'   => 'forbidden_request',
              'message' => 'Unable to change the status to ONGOING. Current status is: '. $offer->status,
          ], 403);
      }
      $offer->status = Offer::STATUS['ONGOING'];
      $offer->save();
      return response()->json([
          'message' => 'Offer set to ONGOING successfully.',
          'data'    => $offer,
      ]);
  }
    /**
     * Set an offer status to completed
     *
     * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
     *
     * Status: Cancelled by User= 0, Pending = 1, Ongoing = 2, Completed = 3, Expired by server = 4.
     *
     * Returns Success.
     *
     */
    public function setCompleted($id){
        $offer = Offer::find($id);
        if (!$offer) {
            return response()->json([
                'error'   => 'offer_not_found',
                'message' => 'Offer does not exist.',
            ], 404);
        }
        if ($offer->user_id != Auth::user()->id) {
            return response()->json([
                'error'   => 'forbidden_request',
                'message' => 'User does not have permission to edit this offer.',
            ], 403);
        }
        if ($offer->status != Offer::STATUS['ONGOING']) { //can only change ONGOING -> COMPLETED
            return response()->json([
                'error'   => 'forbidden_request',
                'message' => 'Unable to change the status to COMPLETED. Current status is: '. $offer->status,
            ], 403);
        }
        $offer->status = Offer::STATUS['COMPLETED'];
        $offer->save();
        $offer->bookings->delete();

        return response()->json([
            'message' => 'Offer set to COMPLETED successfully.',
            'data'    => $offer,
        ]);
    }
  /**
   * Get offers from Date
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * If no date given, today's date is used.
   *
   * Returns all offers on a requested date
   *
   */
  public function getDatesOffers(GetDate $request)
  {

    //if no date requested, set to today's date
    if (!isset($request->date) || empty($request->date)) {
      $current = Carbon::today();
    } else {
      $current = $request->date; //set to requested date
    }

    $next = $current->addDay(1);
    //get all offers before DATE + 1day at 00:00
    $offers = Offer::where('meetup_time', '<', $next)
      ->where('meetup_time', '>=', $current)
      ->get();

    return response()->json([
      'data' => $offers,
    ], 200);
  }
  /**
   * Get offers belonging to a user
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Returns all offers belonging to user($id)
   *
   */
  public function getUsersOffers(GetUserId $request)
  {
    //if user_id not passed (which it shouldn't be anyways)
    if (!isset($request->user_id) || empty($request->user_id)) {
      $user_id = Auth::user()->id; //set user id to current user
    } else {
      $user_id = $request->user_id;
    }

    $offers = Offer::where('user_id', $user_id)->get();

    return response()->json([
      'data' => $offers,
    ], 200);
  }
  /**
   * Get nearby offers
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Range accepts 1-12 (Precision of geohash, 1 = ~5000km, 12 = 3.7 cm. Defaults to 4)
   *
   * Returns all nearby offers in the next 24 hours.
   *
   */
  public function getNearby(GetNearby $request)
  {

    if (!isset($request->range) || empty($request->range)) {
      $range = 4; //set user id to current user
    } else {
      $range = $request->range;
    }

    $currenthash = Geohash::encode($request->lat, $request->lng); // hash current location
    $shortenby   = $range - strlen($currenthash);
    $searchhash  = substr($currenthash, 0, $shortenby);

    $now   = Carbon::now();
    $limit = Carbon::now()->addHours(24);

    $offers = Offer::where('status', Offer::STATUS['PENDING'])->where('meetup_time', '<=', $limit)->where('meetup_time', '>', $now)->where('start_geohash', 'LIKE', $searchhash . '%')->get();

    foreach ($offers as $offer) {
      $totalpax = 0;
      foreach ($offer->bookings as $booking) {
        $totalpax = $totalpax + $booking->pax;
      }
      $offer['seats_booked']    = $totalpax;
      $offer['seats_remaining'] = $offer->vacancy - $totalpax;
      $offer['name']            = $offer->user->name;
    }
    return response()->json([
      'data' => $offers,
    ], 200);
  }
}

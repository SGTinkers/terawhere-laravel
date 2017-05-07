<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetDate;
use App\Http\Requests\GetUserId;
use App\Http\Requests\GetOfferId;
use App\Http\Requests\StoreBooking;
use App\Booking;
use App\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @resource Booking
 *
 * All bookings by passengers are handled here.
 */

class BookingController extends Controller
{
  /**
   * Get all bookings
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Returns all bookings in database
   *
   */
  public function index()
  {
    $bookings = Booking::all();
    return response()->json([
      'data' => $bookings,
    ], 200);
  }
  /**
   * Show a particular booking
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Returns a single booking
   *
   */
  public function show($id)
  {
    $booking = Booking::find($id);

    if (!$booking) {
      return response()->json([
        'error' => [
          'message' => 'Booking does not exist.',
        ],
      ], 404);
    }

    return response()->json([
      'data' => $booking,
    ], 200);
  }

  /**
   * Create a booking
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Returns Success or error message.
   *
   */
  public function store(StoreBooking $request)
  {
    //BUSINESS LOGIC FOR BOOKING-OFFER RELATION

    $data            = $request->all();
    $data['user_id'] = Auth::user()->id;
    $offer           = Offer::where('id', $data->offer_id)->first(); //get offer

    if (!$offer) {
      return response()->json([
        'error' => [
          'message' => 'That offer does not exist.',
        ],
      ], 422);
    }

    $bookings       = Booking::where('offer_id', $data->offer_id)->get();
    $dateToday      = date('Y-m-d').' 23:59'; //inclusive of
    $todaysBookings = Booking::where('meetup_time', '<=', $dateToday); //get bookings from today
    $dailyLimit     = 3; //SET DAILY BOOKING LIMIT HERE

    $usersBookings   = Booking::where('user_id', Auth::user()->id)->get();

    if (count($bookings) >= $offer->vacancy) {
      return response()->json([
        'error' => [
          'message' => 'There is no more vacancy for that offer.',
        ],
      ], 422);
    }

    //checking to see if same user books twice
    foreach ($bookings as $booking) {
      if ($booking->user_id == $data->user_id) {
        return response()->json([
          'error' => [
            'message' => 'The same user cannot book an offer more than once.',
          ],
        ], 422);
      }
    }

    if(count($usersBookings) > 1){
      return response()->json([
          'error' => [
            'message' => 'You already have an active booking.',
          ],
        ], 422);
    }

    foreach ($todaysBookings as $booking) {
      if (count($booking->user_id) >= $dailyLimit) {
        return response()->json([
          'error' => [
            'message' => 'User has reached daily booking limit.',
          ],
        ], 422);
      }
    }

    $booking = Booking::create($data);

    return response()->json([
      'message' => 'Booking added succesfully.',
      'data'    => $booking,
    ], 200);

  }
  /**
   * Cancel a booking
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Returns all offers in database
   *
   */
  public function destroy($id)
  {
    $booking = Booking::find($id);
    if (!$booking) {
      return response()->json([
        'error' => [
          'message' => 'Booking does not exist.',
        ],
      ], 404);
    }
    $booking->delete(); //booking is soft deleted.

    //Aziz: To add push notif here to tell driver that booking is cancelled.

    return response()->json([
      'message' => 'Booking deleted succesfully.',
      'data'    => $booking,
    ]);
  }

  /**
   * Get all bookings belonging to an offer
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Returns all bookings made to an offer or 404
   *
   */
  public function getOffersBookings(GetOfferId $request)
  {
    $bookings = Booking::where('offer_id', $request->offer_id)->get();
    if ($bookings->isEmpty()) {
      return response()->json([
        'error' => [
          'message' => 'Selected offer does not have any bookings.',
        ],
      ], 404);
    }
    return response()->json([
      'count' => count($bookings),
      'data'  => $bookings,
    ], 200);
  }

   /**
   * Get all bookings by date
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Returns all bookings made on requested date or 404
   *
   */ 
  public function getDatesBookings(GetDate $request)
  { 
    //if no date requested, set to today's date
    if(!isset($request->date) || empty($request->date)){
      $current = date('Y-m-d') .' 00:00';
    }else{
      $current = $request->date .' 00:00'; //set to requested date
    }

    $next = date('Y-m-d', strtotime('+1 day', $current));
    //get all offers before DATE + 1day at 00:00
    $bookings = Booking::where('created_at', '<', $next)
              ->where('created_at','>', $current)
              ->get();
    
    if ($bookings->isEmpty()) {
      return response()->json([
        'error' => [
          'message' => 'There are no bookings on this date.',
        ],
      ], 404);
    }
      return response()->json([
        'data' => $bookings,
      ], 200);
  }
}

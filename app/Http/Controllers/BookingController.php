<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Http\Requests\GetOfferId;
use App\Http\Requests\StoreBooking;
use App\Notifications\PassengerCancelBooking;
use App\Notifications\PassengerMadeBooking;
use App\Offer;
use App\User;
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
    $bookings = Booking::withTrashed()->with('offer')->get();
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
    $booking = Booking::with('offer')->find($id);

    if (!$booking) {
      return response()->json([
        'error'   => 'booking_not_found',
        'message' => 'Booking does not exist.',
      ], 404);
    }
    $booking['name']   = $booking->user->name;
    $booking['gender'] = $booking->user->gender;

    return response()->json([
      'data' => $booking,
    ], 200);
  }

  /**
   * Create a booking
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Do not use pax, pax defaults to 1. For future use ONLY.
   *
   * Returns Success or error message.
   *
   */
  public function store(StoreBooking $request)
  {
    //BUSINESS LOGIC FOR BOOKING-OFFER RELATION

    $data            = $request->all();
    $data['user_id'] = Auth::user()->id;
    $offer           = Offer::where('id', $data['offer_id'])->first(); //get offer

    if (!$offer) {
      return response()->json([
        'error'   => 'offer_not_found',
        'message' => 'That offer does not exist.',
      ], 422);
    }

    $bookings = $offer->bookings;

    if ($offer->user_id == Auth::user()->id) {
      return response()->json([
        'error'   => 'cannot_book_own_offer',
        'message' => 'User cannot book their own offer.',
      ], 422);
    }

    $totalpax = $request->pax;

    foreach ($bookings as $booking) {
      $totalpax = $totalpax + $booking->pax;
    }

    if ($totalpax >= $offer->vacancy) {
      return response()->json([
        'error'   => 'no_more_vacancy',
        'message' => 'There is no more vacancy for that offer.',
      ], 422);
    }

    //checking to see if same user books twice
    foreach ($bookings as $booking) {
      if ($booking->user_id == $data['user_id']) {
        return response()->json([
          'error'   => 'already_booked',
          'message' => 'The same user cannot book an offer more than once.',
        ], 422);
      }
    }

    $activeBooking = Booking::where('bookings.user_id', Auth::user()->id)->active()->first();
    if ($activeBooking) {
      return response()->json([
        'error'   => 'active_booking_exists',
        'message' => 'User already have an active booking.',
      ], 422);
    }

    $booking = Booking::create($data);

    // Send push notification to driver
    $booking->offer->user->notify(new PassengerMadeBooking($booking->offer->user, $booking));

    return response()->json([
      'message' => 'Booking added successfully.',
      'data'    => $booking,
    ], 200);

  }

  /**
   * Cancel a booking
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Returns success message or 404.
   */
  public function destroy($id)
  {
    $booking = Booking::find($id);
    if (!$booking) {
      return response()->json([
        'error'   => 'booking_not_found',
        'message' => 'Booking does not exist.',
      ], 404);
    }

    if ($booking->user_id != Auth::user()->id) {
      return response()->json([
        'error'   => 'forbidden_request',
        'message' => 'User does not have permission to delete this booking.',
      ], 403);
    }

    $booking->delete(); //booking is soft deleted.

    // Aziz: To add push notif here to tell driver that booking is cancelled.
    $booking->offer->user->notify(new PassengerCancelBooking($booking->offer->user, $booking));

    return response()->json([
      'message' => 'Booking deleted successfully.',
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
  public function getOffersBookings($id)
  {
    $offer = Offer::where('id', $id)->first();

    if (!$offer) {
      return response()->json([
        'error'   => 'offer_not_found',
        'message' => 'Selected offer does not exist.',
      ], 404);
    }

    $bookings = Booking::with('user')->where('offer_id', $id)->get();

    return response()->json([
      'data' => $bookings,
    ], 200);
  }

/**
 * Get bookings belonging to a user
 *
 * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
 *
 * Returns all offers belonging to user($id)
 *
 */
  public function getUsersBookings()
  {
    $bookings = Booking::with([
        'offer' => function ($q) {
            $q->orderBy('meetup_time', 'desc');
            $q->orderBy('status', 'asc');
        },'offer.user'])
        ->withTrashed()
        ->where('user_id', Auth::user()->id)
        ->orderBy('deleted_at', 'asc')
        ->get();

    return response()->json([
      'data' => $bookings,
    ], 200);
  }

}

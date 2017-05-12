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
        'error' => 'Booking_not_found',
        'message' => 'Booking does not exist.'
      ], 404);
    }

    $user = User::find($booking->user_id);
    $booking['name']    = $user->name;
    $booking['gender']  = $user->gender;

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
        'error'   => 'Offer_not_found',
        'message' => 'That offer does not exist.'
        ], 422);
    }

    $bookings       = Booking::where('offer_id', $data['offer_id'])->get();
    $dateToday      = date('Y-m-d').' 23:59'; //inclusive of
    $usersBookings  = Booking::where('user_id', Auth::user()->id)->get();

    if($offer->user_id == Auth::user()->id){
      return response()->json([
        'error'     => 'Invalid_request', 
        'message'   => 'User cannot book their own offer.'        
      ], 422);
    }

    $totalpax = 0;

    foreach($bookings as $booking){
      $totalpax = $totalpax + $booking->pax; 
    }

    if ($totalpax >= $offer->vacancy) {
      return response()->json([
        'error'     => 'Invalid_request', 
        'message'   => 'There is no more vacancy for that offer.'        
      ], 422);
    }

    //checking to see if same user books twice
    foreach ($bookings as $booking) {
      if ($booking->user_id == $data['user_id']) {
        return response()->json([
          'error'   => 'Invalid_request',
          'message' => 'The same user cannot book an offer more than once.'
        ], 422);
      }
    }

    if(count($usersBookings) >= 1){
      return response()->json([
          'error'   => 'Invalid_request',
          'message' => 'User already have an active booking.'
        ], 422);
    }

    $booking = Booking::create($data);

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
        'error' => 'Booking_not_found',
        'message' => 'Booking does not exist.'
      ], 404);
    }

    if($booking->user_id != Auth::user()->id){
      return response()->json([
        'error' => 'Forbidden_request',
        'message' => 'User does not have permission to delete this booking.'
        ], 403);
    }

    $booking->delete(); //booking is soft deleted.

    //Aziz: To add push notif here to tell driver that booking is cancelled.

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
  public function getOffersBookings(GetOfferId $request)
  {
    $bookings = Booking::where('offer_id', $request->offer_id)->get();
    $offers   = Offer::where('id', $request->offer_id)->get();

    if ($offers->isEmpty()) {
      return response()->json([
        'error' => 'Offer_not_found',
        'message' => 'Selected offer does not exist.'
        ], 404);
    }

    if ($bookings->isEmpty()) {
      return response()->json([
        'error' => 'Booking_not_found',
        'message' => 'Selected offer does not have any bookings.'
        ], 404);
    }

    return response()->json([
      'count'   => count($bookings),
      'message' => 'There are '.count($bookings).' bookings for this offer',
      'data'    => $bookings,
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
  public function getUsersBookings(GetUserId $request)
  {
    //if user_id not passed (which it shouldn't be anyways)
    if(!isset($request->user_id) || empty($request->user_id)){
        $user_id = Auth::user()->id; //set user id to current user
    } else {
        $user_id = $request->user_id;
    }

    $bookings = Booking::withTrashed()->where('user_id', $user_id)->get();
    
    if ($bookings->isEmpty()) {
      return response()->json([
        'error' => 'Resource_not_found',
        'message' => 'User does not have any bookings.',
        ], 404);
    }

    else {
      return response()->json([
        'data' => $bookings,
      ], 200);
    }
  }

}

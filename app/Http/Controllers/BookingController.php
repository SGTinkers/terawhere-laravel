<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Booking;

class BookingController extends Controller
{
    /*public function __construct(){
        $this->middleware('jwt.auth');
	}*/

	public function index(){
    $bookings = Booking::all();
    return \Response::json([
         'message' => $bookings
    ], 200);
	}

	public function show($id){
        $booking = Booking::find($id);

        if(!$booking){
            return \Response::json([
                'error' => [
                    'message' => 'Booking does not exist'
                ]
            ], 404);
        }
   		  
        return \Response::json([
                'data' => $booking
        ], 200);
	}

	public function store(Request $request)
    {
 
        $this->validate($request, [
        'user_id' => 'required',
    	]); //TO BE IMPLEMENTED

        $offer = Offer::where('id', $request->offer_id)->get();
        $vacancy = $offer->vacancy;
        $booking = Booking::where('offer_id', $request->offer_id)->get();

        //if number of bookings for that OFFER_ID < vacancy of that OFFER_ID, then create new booking

        if(count($booking) < $vacancy){
        $booking = Booking::create($request->all());
 
        return Response::json([
                'message' => 'Booking added succesfully',
                'data' => $booking
        ], 200);
    	}
    	else{
    	return Response::json([
                'error' => [
                    'message' => 'There is no more vacancy for that offer_id'
                ]
            ], 422);
    	}
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);
        $booking->delete(); //booking is soft deleted.
    }

    public function getUsersBookings(){

    }

}

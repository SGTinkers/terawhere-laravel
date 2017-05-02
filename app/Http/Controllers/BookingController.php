<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Booking;
use App\Offer;

class BookingController extends Controller
{
    /*public function __construct(){
        $this->middleware('jwt.auth');
	}*/

	public function index(){
    $bookings = Booking::all();
    return \Response::json([
         'data' => $bookings
    ], 200);
	}

	public function show($id){
        $booking = Booking::find($id);

        if(!$booking){
            return \Response::json([
                'error' => [
                    'message' => 'Booking does not exist.'
                ]
            ], 404);
        }
   		  
        return \Response::json([
                'data' => $booking
        ], 200);
	}

	public function store(Request $request)
    {
 		
    	$rules = [
            'user_id' => 'required|integer',
            'offer_id' => 'required|integer',
            'status' => 'required|integer'
    	]; 

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
        return \Response::json(['errors'=>$validator->errors()]);
        }


        $offer = Offer::where('id', $request->offer_id)->first();
       
       	if(!$offer){
    	return \Response::json([
                'error' => [
                    'message' => 'That offer does not exist.'
                ]
            ], 422);
    	}

        $bookings = Booking::where('offer_id', $request->offer_id)->get();
        $allBookings = Booking::all();
        $dailyLimit = 2;

        if(count($bookings) >= $offer->vacancy){
    	return \Response::json([
                'error' => [
                    'message' => 'There is no more vacancy for that offer_id.'
                ]
            ], 422);
    	}
    	
    	//checking to see if same user books twice
    	foreach($bookings as $booking){
    		if($booking->user_id == $request->user_id){
    		return \Response::json([
                'error' => [
                    'message' => 'The same user cannot book an offer more than once.'
                ]
            ], 422);
    		}
    	}

    	foreach ($allBookings as $booking){
    		if(count($booking->user_id) >= $dailyLimit){
    			return \Response::json([
                'error' => [
                    'message' => 'User has reached daily booking limit.'
                ]
            ], 422);
    		}
    	}

    	$booking = Booking::create($request->all());
 
        return \Response::json([
                'message' => 'Booking added succesfully.',
                'data' => $booking
        ], 200);
    	
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);
        if(!$booking){
            return \Response::json([
                'error' => [
                    'message' => 'Booking does not exist.'
                ]
            ], 404);
        }
        $booking->delete(); //booking is soft deleted.

        return \Response::json([
                'message' => 'Booking deleted succesfully.',
                'data' => $booking
        ]);
    }

    public function getUsersBookings($id){
    	$bookings = Booking::where('user_id',$id)->get();
		if($bookings->isEmpty()){
            return \Response::json([
                'error' => [
                    'message' => 'User_id does not own any bookings.'
                ]
            ], 404);
        }

        return \Response::json([
                'data' => $bookings
        ], 200);

    }
    public function getOffersBookings($id){
        $booking = Booking::where('offer_id', $id)->get();
        if($booking->isEmpty()){
            return \Response::json([
                'error' => [
                    'message' => 'Offer_id does not have any bookings.'
                ]
            ], 404);
        }
        return \Response::json([
                'data' => $booking
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Offer;

class OfferController extends Controller
{
	public function __construct(){
        $this->middleware('jwt.auth');
	}

	public function index(){
    $offers = Offer::all();
    return \Response::json([
         'message' => $offers
    ], 200);
	}

	public function show($id){
        $offer = Offer::find($id);

        if(!$offer){
            return \Response::json([
                'error' => [
                    'message' => 'Offer does not exist'
                ]
            ], 404);
        }
   		  
        return \Response::json([
                'data' => $offer
        ], 200);
	}

	
    public function store(Request $request)
    {
 
        if(!$request->user_id){
            return Response::json([
                'error' => [
                    'message' => 'Please provide user_id'
                ]
            ], 422);
        }elseif(!$request->meetup_time){
            return Response::json([
                'error' => [
                    'message' => 'Please provide meetup_time'
                ]
            ], 422);
        }elseif(!$request->start_name or !$request->start_addr or !$request->start_lat or !$request->start_lng){
            return Response::json([
                'error' => [
                    'message' => 'One or more of the starting location details are missing (name, address, lat, lng).'
                ]
            ], 422);
        }elseif(!$request->end_name or !$request->end_addr or !$request->end_lat or !$request->end_lng){
            return Response::json([
                'error' => [
                    'message' => 'One or more of the arrival (end) location details are missing (name, address, lat, lng).'
                ]
            ], 422);
        }

		$this->validate($request, [
        'user_id' => 'required',
        'meetup_time' => 'required'
    	]); //TO BE IMPLEMENTED

        $offer = Offer::create($request->all());
 
        return Response::json([
                'message' => 'Offer added succesfully',
                'data' => $offer
        ]);
    }

   	public function destroy($id)
    {
        $offer = Offer::find($id);
        $offer->delete(); //offer is soft deleted.
    }

    public function getNearbyActiveOffers(){


    }

    public function getUserActiveOffers(){
        
    }

	/*private function transform($offer){ //to omit certain fields
    return [
           'offer_id' => $offer['id'],
           'time' => $offer['meetup_time']
        ];
	}
	*/
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Offer;

class OfferController extends Controller
{
	/*public function __construct(){
        $this->middleware('jwt.auth');
	}*/

	public function index(){
    $offers = Offer::all();
    return \Response::json([
         'data' => $offers
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
 
		$rules = [
            'user_id' => 'required|integer',
            'meetup_time' => 'required|date_format:Y-m-d H:i',
            'start_name' => 'required',
            'start_addr' => 'required',
            'start_lat' => 'required|numeric|between:-90,90',
            'start_lng' => 'required|numeric|between:-180,180',
            'end_name' => 'required',
            'end_addr' => 'required',
            'end_lat' => 'required|numeric|between:-90,90',
            'end_lng' => 'required|numeric|between:-180,180',
            'vacancy' => 'required|integer',
            'pref_gender' => 'integer|between:0,1'
    	]; 

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
        return \Response::json([
            'errors'    =>  $validator->errors()
            ], 422);
        }

        $offer = Offer::create($request->all()); 
        return \Response::json([
                'message' => 'Offer added succesfully.',
                'data' => $offer
        ], 200);
    }

    public function update(Request $request, $id){

        $offer = Offer::find($id);

        //VALIDATION NOT YET IMPLEMENTED
        $this->validate($request, [
        'user_id' => 'required',
        'meetup_time' => 'required'
        ]); //TO BE IMPLEMENTED

        $offer = Offer::findOrFail($id)->fill($request->all());
        $offer->save();
 
        return \Response::json([
                'message' => 'Offer updated succesfully.',
                'data' => $offer
        ]);


    }

   	public function destroy($id)
    {
        $offer = Offer::find($id);
        if(!$offer){
            return \Response::json([
                'error' => [
                    'message' => 'Offer does not exist.'
                ]
            ], 404);
        }
        $offer->delete(); //offer is soft deleted.

        return \Response::json([
                'message' => 'Offer deleted succesfully.',
                'data' => $offer
        ]);
    }

    public function getUsersOffers($id){
        $offers = Offer::where('user_id',$id)->get();
        if($offers->isEmpty()){
            return \Response::json([
                'error' => [
                    'message' => 'User does not have any offers.'
                ]
            ], 404);
        }
        else{
        return \Response::json([
                'data' => $offers
        ], 200);
        }
    }
    public function getNearbyActiveOffers(){


    }

    public function getNearby(){
        
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

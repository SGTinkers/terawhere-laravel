<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOffer;
use App\Offer;
use Illuminate\Http\Request;
use Validator;

class OfferController extends Controller
{
  /*public function __construct(){
  $this->middleware('jwt.auth');
  }*/

  public function index()
  {
    $offers = Offer::all();
    return \Response::json([
      'data' => $offers,
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

    public function store(StoreOffer $request)
    {
        $offer = Offer::create($request->all()); 
        return \Response::json([
                'message' => 'Offer added successfully.',
                'data' => $offer
        ], 200);
    }

    public function update(UpdateOffer $request, $id){
                
        $offer = Offer::find($id);

        if(!$offer){
            return \Response::json([
                'error' => [
                    'message' => 'Offer does not exist.'
                ]
            ], 404);
        }

        $offer->fill($request->all());
        $offer->save();
 
        return \Response::json([
                'message' => 'Offer updated successfully.',
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
                'message' => 'Offer deleted successfully.',
                'data' => $offer
        ]);

    }


  public function getUsersOffers($id)
  {
    $offers = Offer::where('user_id', $id)->get();
    if ($offers->isEmpty()) {
      return \Response::json([
        'error' => [
          'message' => 'User does not have any offers.',
        ],
      ], 404);
    } else {
      return \Response::json([
        'data' => $offers,
      ], 200);
    }
  }
  public function getNearbyActiveOffers()
  {

  }

  public function getNearby(Request $request)
  {

  }

  public function getUserActiveOffers()
  {

  }

  /*private function transform($offer){ //to omit certain fields
return [
'offer_id' => $offer['id'],
'time' => $offer['meetup_time']
];
}
 */
}

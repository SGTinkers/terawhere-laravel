<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOffer;
use App\Http\Requests\UpdateOffer;
use App\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

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
   * @return all offers in database
   *
   */
  public function index()
  {
    $offers = Offer::all();
    return response()->json([
      'data' => $offers,
    ], 200);
  }
  
  /**
   * Show a single offer
   *
   * @return a single offer from id 
   *
   */
  public function show($id)
  {
    $offer = Offer::find($id);
    if (!$offer) {
      return response()->json([
        'error' => [
          'message' => 'Offer does not exist',
        ],
      ], 404);
    }

    return response()->json([
      'data' => $offer,
    ], 200);
  }
  
  /**
   * Store an offer
   *
   * @return Validation errors OR success message w/ data posted
   *
   */
  public function store(StoreOffer $request)
  {
    $data            = $request->all();
    $data["user_id"] = Auth::user()->id;
    $offer           = Offer::create($data);
    return response()->json([
      'message' => 'Offer added successfully.',
      'data'    => $offer,
    ], 200);
  }
  /**
   * Update an offer
   * 
   * @return Success message or 404.
   *
   */
  public function update(UpdateOffer $request, $id)
  {
    $offer = Offer::find($id);

    if (!$offer) {
      return response()->json([
        'error' => [
          'message' => 'Offer does not exist.',
        ],
      ], 404);
    }

    $offer->fill($request->all());
    $offer->save();

    return response()->json([
      'message' => 'Offer updated successfully.',
      'data'    => $offer,
    ]);
  }

  /**
   * Cancel an offer
   *
   * @return Success or 404.
   *
   */
  public function destroy($id)
  {
    $offer = Offer::find($id);
    if (!$offer) {
      return response()->json([
        'error' => [
          'message' => 'Offer does not exist.',
        ],
      ], 404);
    }
    $offer->delete(); //offer is soft deleted.

    //Aziz: To add push notif here to tell passengers that offer is cancelled.

    return response()->json([
      'message' => 'Offer deleted successfully.',
      'data'    => $offer,
    ]);

  }
  /**
   * Get offers belonging to a user
   *
   * @return all offers belonging to user($id)
   *
   */
  public function getUsersOffers($id)
  {
    $offers = Offer::where('user_id', $id)->get();
    if ($offers->isEmpty()) {
      return response()->json([
        'error' => [
          'message' => 'User does not have any offers.',
        ],
      ], 404);
    } else {
      return response()->json([
        'data' => $offers,
      ], 200);
    }
  }

  public function getNearby(GetNearbyOffers $request)
  {
    $range = 3959;
    $max_distance = 20;
    $lat = $request->lat;
    $lng = $request->lng;
    $range = $request->range;
    $offers = Offer::all();

    $result = collect([]);

    foreach($offers as $offer) {
      $dist = $this->haversineGreatCircleDistance($lat, $lng, $offer->start_lat, $offer->start_lng);
      
      if ($dist <= $range){ 
      //if distance between given coords and coord of offers is < range, add to collection
      $result->push($offer);
      }
    }

    return $result->all();
  }

  public function haversineGreatCircleDistance( 
  $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
  {
  $earthRadius = 6371000;
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  return $angle * $earthRadius;
  }

  /*private function transform($offer){ //to omit certain fields
return [
'offer_id' => $offer['id'],
'time' => $offer['meetup_time']
];
}
 */
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetUserId;
use App\Http\Requests\GetOfferId;
use App\Http\Requests\GetDate;
use App\Http\Requests\GetNearby;
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
  private $simple = ['meetup_time', 'start_name','end_name','vacancy','status','pref_gender'];
  /**
   * Get all offers
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   * Not recommended for use
   * Returns ALL offers in database
   *
   */
  public function index(Request $request)
  {
    $offers = Offer::all();

    //if simple tag is set, only return certain fields
    if ($request->simple == true) {
      $filtered = $offers->only($this->simple);
      
      return response()->json([
        'data' => $filtered->all(),
      ], 200);
    }

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
        'error' => [
          'message' => 'Offer does not exist',
        ],
      ], 404);
    }
  //if simple tag is set, only return certain fields
    if ($request->simple == true) {
      $filtered = $offers->only($this->simple);
      
      return response()->json([
        'data' => $filtered->all(),
      ], 200);
    }

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
    $offer           = Offer::create($data);
    
    //if simple tag is set, only return certain fields
    if ($request->simple == true) {
      $filtered = $offers->only($this->simple);
      
      return response()->json([
        'data' => $filtered->all(),
      ], 200);
    }

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
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   *
   * Returns Success or 404.
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
   * Get offers from Date
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   * Send a simple = true, to get a summarised version of offer.
   *
   * If no date given, today's date is used.
   *
   * Returns all offers on a requested date
   *
   */
  public function getDatesOffers(GetDate $request)
  { 

    //if no date requested, set to today's date
    if(!isset($request->date) || empty($request->date)){
      $current = date('Y-m-d') +' 00:00';
    }else{
      $current = $request->date +' 00:00'; //set to requested date
    }

    $next = date('Y-m-d', strtotime('+1 day', $current));
    //get all offers before DATE + 1day at 00:00
    $offers = Offer::where('created_at', '<', $next)
              ->where('created_at','>', $current)
              ->get();
    
    if ($offers->isEmpty()) {
      return response()->json([
        'error' => [
          'message' => 'There are no offers on this date.',
        ],
      ], 404);
    }

    //if simple tag is set, only return certain fields
    if ($request->simple == true) {
      $filtered = $offers->only($this->simple);
      
      return response()->json([
        'data' => $filtered->all(),
      ], 200);
    }

      return response()->json([
        'data' => $offers,
      ], 200);
  }
  /**
   * Get offers belonging to a user
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   * Send a simple = true, to get a summarised version of offer.
   * Returns all offers belonging to user($id)
   *
   */
  public function getUsersOffers(GetUserId $request, $id)
  {
    //if user_id not passed (which it shouldn't be anyways)
    if(!isset($request->user_id) || empty($request->user_id)){
        $user_id = Auth::user()->id; //set user id to current user
    } else {
        $user_id = $request->user_id;
    }

    $offers = Offer::where('user_id', $user_id)->get();
    
    if ($offers->isEmpty()) {
      return response()->json([
        'error' => [
          'message' => 'User does not have any offers.',
        ],
      ], 404);
    } 
    
    //if simple tag is set, only return certain fields
    if ($request->simple == true) {
      $filtered = $offers->only($this->simple);
      
      return response()->json([
        'data' => $filtered->all(),
      ], 200);
    }

    else {
      return response()->json([
        'data' => $offers,
      ], 200);
    }
  }
  /**
   * Get nearby offers
   *
   * **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
   * Send a simple = true, to get a summarised version of offer.
   * Returns all nearby offers (To be optimised)
   *
   */
  public function getNearby(GetNearby $request)
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
    //if simple tag is set, only return certain fields
    if ($request->simple == true) {
      $filtered = $offers->only($this->simple);
      
      return response()->json([
        'data' => $filtered->all(),
      ], 200);
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

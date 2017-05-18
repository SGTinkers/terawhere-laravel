<?php

namespace App\Http\Controllers;

use App\Review;
use App\Offer;
use App\Booking;
use App\Http\Requests\GetUserId;
use App\Http\Requests\StoreReview;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

/**
 * @resource Review
 *
 * All reviews by users are handled here.
 *
 */

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = Review::all();
        return response()->json([
        'data' => $reviews,
        ], 200);
    }

    /**
     * Create a new review
     *
     * To review an offer (and its driver), send offer_id.
     *
     * To review a booking (and its passenger), send booking_id.
     * 
     * But don't send BOTH offer_id and booking_id. will fail.
     * 
     */
    public function store(StoreReview $request)
    {
        $data = $request->all();
        $data['reviewer_id'] = Auth::user()->id;

        if(isset($data['offer_id']) && isset($data['booking_id'])){
            return response()->json([
            'error' =>   'Invalid_request',
            'message' => 'Unable to process both offer and booking. Please use only offer_id or booking_id but not both.'
            ], 422);
        }   

        if(isset($data['offer_id'])){
            
            $offer = Offer::find($data['offer_id']);

            if (!$offer) {
                return response()->json([
                'error' => 'Resource_not_found',
                'message' => 'Offer does not exist.'
                ], 404);
            }

            $data['user_id'] = $offer->user_id;

            $review = Review::create($data);
            return response()->json([
            'message' => 'Review added successfully.',
            'data'    => $review,
            ], 200);
        }

        if(isset($data['booking_id'])){
            
            $booking = Booking::find($data['booking_id']);

            if (!$booking) {
                return response()->json([
                'error' => 'Resource_not_found',
                'message' => 'Booking does not exist.'
                ], 404);
            }

            $data['user_id'] = $booking->user_id;

            $review = Review::create($data);
            return response()->json([
            'message' => 'Review added successfully.',
            'data'    => $review,
            ], 200);
        }
    }

    /**
     * Display the specified review.
     *
     * 
     * 
     */
    public function show($id)
    {
        $review = Review::find($id);
        if (!$review) {
          return response()->json([
            'error' => 'Resource_not_found',
            'message' => 'Review does not exist.'
          ], 404);
        }

        return response()->json([
          'data' => $review,
        ], 200);
    }

    public function getUsersReviews(GetUserId $request){
        //if user_id not passed (which it shouldn't be anyways)
        if(!isset($request->user_id) || empty($request->user_id)){
            $user_id = Auth::user()->id; //set user id to current user
        } else {
            $user_id = $request->user_id;
        }

        $reviews = Review::where('user_id', $user_id)->get();
        
        if ($reviews->isEmpty()) {
          return response()->json([
            'error' => 'Resource_not_found',
            'message' => 'User has not been reviewed.'
          ], 404);
        } 
        
            return response()->json([
                'data' => $reviews,
            ], 200);
    }

    public function getUsersRatings(){
        //if user_id not passed (which it shouldn't be anyways)
        if(!isset($request->user_id) || empty($request->user_id)){
            $user_id = Auth::user()->id; //set user id to current user
        } else {
            $user_id = $request->user_id;
        }

        $reviews = Review::where('user_id', $user_id)->get();
        
        if ($reviews->isEmpty()) {
          return response()->json([
            'error' => 'Resource_not_found',
            'message' => 'User has not been reviewed.'
          ], 404);
        } 
        
        $totalratings = 0;

        foreach($reviews as $review){
            $totalratings = $totalratings + $review->rating;
        }

        $avgrating = $totalratings / count($reviews);
        return response()->json([
                'data' => $avgrating,
        ], 200);
    }

    public function getReviewersReviews(GetUserId $request){
        if(!isset($request->user_id) || empty($request->user_id)){
            $user_id = Auth::user()->id; //set user id to current user
        } else {
            $user_id = $request->user_id;
        }

        $reviews = Review::where('reviewer_id', $user_id)->get();
        
        if ($reviews->isEmpty()) {
          return response()->json([
            'error' => 'Resource_not_found',
            'message' => 'User has not made any reviews.'
          ], 404);
        } 
        
        return response()->json([
                'data' => $reviews,
        ], 200);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        //
    }
}

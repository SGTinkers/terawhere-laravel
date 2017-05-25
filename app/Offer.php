<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
  use SoftDeletes;

  const STATUS = [
    'CANCELLED' => 0,
    'PENDING'   => 1,
    'ONGOING'   => 2,
    'COMPLETED' => 3,
    'EXPIRED'   => 4,
  ];

  protected $fillable = ['user_id', 'meetup_time', 'start_name', 'start_addr', 'start_lat', 'start_lng', 'end_name', 'end_addr', 'end_lat', 'end_lng', 'vacancy', 'remarks', 'status', 'pref_gender', 'vehicle_number', 'vehicle_desc', 'vehicle_model', 'start_geohash', 'end_geohash',
  ];
  protected $dates = ['deleted_at'];

  protected $hidden = ['bookings'];

  public static function boot()
    {
        parent::boot();

        // Attach event handler, on deleting of the offer
        Offer::deleting(function($offer)
        {
            // Delete all bookings that belong to this offer
            foreach ($offer->bookings as $booking) {
                $booking->delete();
            }
        });
    }
  /**
   * Scope a query to only include active offers.
   */
  public function scopeActive($query)
  {
    $now = Carbon::now();
    return $query->where('offers.status', Offer::STATUS['PENDING'])->where('offers.meetup_time', '<', $now);
  }

  public function user()
  {
    return $this->belongsTo('App\User')->select(array('id', 'name', 'email', 'dp'));
  }

  public function bookings()
  {
    return $this->hasMany('App\Booking');
  }

  public function reviews()
  {
    return $this->hasMany('App\Review');
  }
}

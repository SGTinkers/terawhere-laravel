<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
  use SoftDeletes;

  const STATUS = [
    'CANCELLED'           => 0,
    'PENDING'             => 1,
    'ONGOING'             => 2,
    'COMPLETED'           => 3,
    'EXPIRED'             => 4,
  ];

  protected $fillable = ['user_id', 'meetup_time', 'start_name', 'start_addr', 'start_lat', 'start_lng', 'end_name', 'end_addr', 'end_lat', 'end_lng', 'vacancy', 'remarks', 'status', 'pref_gender', 'vehicle_number', 'vehicle_desc', 'vehicle_model', 'start_geohash', 'end_geohash',
  ];
  protected $dates = ['deleted_at'];

  public function user()
  {
    return $this->belongsTo('App\User');
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

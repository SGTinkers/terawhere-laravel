<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];

  public function offers()
  {
    return $this->belongsTo('App\Offer');
  }
  public function bookings()
  {
    return $this->belongsTo('App\Booking');
  }
  public function users()
  {
    return $this->belongsTo('App\User');
  }

}

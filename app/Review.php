<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];

  public function offer()
  {
    return $this->belongsTo('App\Offer');
  }
  public function booking()
  {
    return $this->belongsTo('App\Booking');
  }
  public function user()
  {
    return $this->belongsTo('App\User');
  }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Offer;
use Carbon\Carbon;

class Booking extends Model
{
  use SoftDeletes;

  protected $fillable = ['user_id', 'offer_id', 'pax'];

  protected $dates = ['deleted_at'];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = ['user'];

  /**
   * Scope a query to only include active bookings.
   */
  public function scopeActive($query)
  {
      $now = Carbon::now();
      return $query->where('status', Offer::STATUS['PENDING'])->where('meetup_time','<', $now);
  }
  public function offer()
  {
    return $this->belongsTo('App\Offer');
  }

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public function review()
  {
    return $this->hasOne('App\Review');
  }
}

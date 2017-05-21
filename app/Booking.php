<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
   * The "booting" method of the model.
   *
   * @return void
   */
  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope('selectBookingsOnly', function (Builder $builder) {
      $builder->select('bookings.*');
    });
  }

  /**
   * Scope a query to only include active bookings.
   */
  public function scopeActive($query)
  {
    $now = Carbon::now();
    return $query->join('offers', 'offers.id', '=', 'bookings.offer_id')->where('offers.status', Offer::STATUS['PENDING'])->where('offers.meetup_time', '<', $now);
  }

  public function offer()
  {
    return $this->belongsTo('App\Offer')->withTrashed();
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

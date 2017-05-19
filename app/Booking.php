<?php

namespace App;

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

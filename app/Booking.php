<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;


class Booking extends Model
{
  use SoftDeletes;

  protected $fillable = ['user_id', 'offer_id', 'status', 'driver_remarks', 'rating'];

  protected $dates = ['deleted_at'];

  public function offers()
  {
    return $this->belongsTo('App\Offer');
  }

  public function users()
  {
    return $this->belongsTo('App\User');
  }

}

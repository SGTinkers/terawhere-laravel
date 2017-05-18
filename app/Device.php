<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
  protected $fillable = ['user_id', 'platform', 'device_token'];

  public function user()
  {
    return $this->belongsTo('App\User');
  }
}

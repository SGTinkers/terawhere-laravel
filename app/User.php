<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use Notifiable;

  /**
   * The default value of attributes if not specified.
   *
   */
  protected $attributes = [
    'exp' => 500,
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'password', 'provider', 'provider_id', 'gender', 'exp',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  public function offers()
  {
    return $this->hasMany('App\Offer');
  }
  public function bookings()
  {
    return $this->hasMany('App\Booking');
  }
}

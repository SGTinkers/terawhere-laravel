<?php

namespace App;

use Alsofronie\Uuid\Uuid32ModelTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use Notifiable, Uuid32ModelTrait;

  /**
   * Use optimized uuid.
   *
   */
  private static $uuidOptimization = true;

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
    'name', 'email', 'password', 'dp', 'gender', 'exp', 'timezone',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token', 'roles'
  ];

  public function offers()
  {
    return $this->hasMany('App\Offer');
  }
  public function bookings()
  {
    return $this->hasMany('App\Booking');
  }
  public function reviews()
  {
    return $this->hasMany('App\Review');
  }
  public function devices()
  {
    return $this->hasMany('App\Device');
  }

  public function devicesTokens()
  {
    $tokens = [];

    $devices = $this->devices;
    foreach ($devices as $device) {
      $tokens[] = $device->device_token;
    }

    return $tokens;
  }

  public function roles(){
      return $this->hasMany('App\Role');
  }
}

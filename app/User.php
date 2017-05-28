<?php

namespace App;

use Alsofronie\Uuid\Uuid32ModelTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

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
    'password', 'remember_token', 'roles',
  ];

  public function offers()
  {
    return $this->hasMany('App\Offer')->withTrashed();
  }
  public function bookings()
  {
    return $this->hasMany('App\Booking')->withTrashed();
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

  public function roles()
  {
    return $this->hasMany('App\Role');
  }

  public function hasRole($role_name)
    {
        foreach ($this->roles()->get() as $role)
        {
            if ($role->role == $role_name)
            {
                return true;
            }
        }
        return false;
    }

  public function isSuspended(){
      $suspended_until = $this->suspended_until;
      $now = Carbon::now();

      if($now < $suspended_until){
          return true;
      }
      return false;
  }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'user_id', 'role',
  ];
  public function users()
  {
    $this->belongsToMany('App\User');
  }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Device extends Model
{
	use SoftDeletes;
	protected $fillable = ['user_id', 'platform', 'device_token'];
	
	public function users()
  	{
    	return $this->belongsTo('App\User');
  	}
}

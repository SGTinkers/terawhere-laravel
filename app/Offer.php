<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
	use SoftDeletes;

    protected $fillable = ['user_id' ,'meetup_time', 'start_name','start_addr','start_lat','start_lng','end_name','end_addr','end_lat','end_lng'];
    protected $dates = ['deleted_at'];

    public function users(){
        return $this->belongsTo('App\User');
    }

    public function users(){
        return $this->hasMany('App\Booking');
    }
}

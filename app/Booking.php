<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public function offers(){
        return $this->belongsTo('App\Offer');
    }
    protected $fillable = ['user_id' ,'offer_id', 'status'];
	
	protected $dates = ['deleted_at'];
}

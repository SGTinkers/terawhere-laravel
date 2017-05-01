<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Booking extends Model
{
	use SoftDeletes;

    protected $fillable = ['user_id' ,'offer_id', 'status'];
	
	protected $dates = ['deleted_at'];

	public function offers(){
        return $this->belongsTo('App\Offer');
    }
    
    public function users(){
        return $this->belongsTo('App\User');
    }
    
}

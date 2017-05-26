<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'title', 'body', 'email', 'os', 'has_replied', 'is_read',
    ];
}

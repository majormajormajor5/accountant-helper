<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function buildings()
    {
        return $this->hasMany('App\Building');
    }
}

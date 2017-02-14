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
        $this->belongsTo('App\User');
    }
}

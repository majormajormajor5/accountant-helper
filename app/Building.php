<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
        'name', 'address', 'organization_id', 'user_id'
    ];

    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

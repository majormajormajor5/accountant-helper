<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    protected $fillable = [
        'number', 'square', 'number_of_residents', 'building_id', 'owners_email'
    ];

    public function building()
    {
        return $this->belongsTo('App\Building');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function owners()
    {
        return $this->belongsToMany('App\Owner');
    }
}

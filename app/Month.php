<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    protected $fillable = [
        'apartment_id',
        'month',
        'beginning_sum',
        'ending_sum',
        'balance',
        'taxes',
        'building_id'
    ];

    public function building()
    {
        return $this->belongsTo('App\Building');
    }

    public function apartment()
    {
        return $this->belongsTo('App\Apartment');
    }
}

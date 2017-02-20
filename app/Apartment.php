<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    protected $fillable = [
        'number', 'square', 'number_of_residents'
    ];

    public function building()
    {
        return $this->belongsTo('App\Building');
    }
}

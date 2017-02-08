<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = [
        'email', 'first_name', 'second_name', 'phone'
    ];
}

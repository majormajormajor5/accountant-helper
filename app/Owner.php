<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = [
        'email', 'first_name', 'second_name', 'patronymic', 'phone', 'user_id'
    ];

    public function apartments()
    {
        return $this->belongsToMany('App\Apartment');
    }

    public function getFullName()
    {
        return $this->second_name . " " .$this->first_name . " " . $this->patronymic;
    }

}

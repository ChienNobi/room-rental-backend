<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationCity extends Model
{
    protected $table = 'location_cities';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'lat',
        'lon',
        'region',
    ];

    public function districts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LocationDistrict::class, 'city_id');
    }
}

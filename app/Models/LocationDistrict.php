<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationDistrict extends Model
{
    protected $table = 'location_districts';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'city_id',
    ];

    /**
     * Get the city that the district belongs to.
     */
    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LocationCity::class, 'city_id');
    }

    /**
     * Get the wards for the district.
     */
    public function wards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LocationWard::class, 'district_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationWard extends Model
{
    protected $table = 'location_wards';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'district_id',
    ];

    /**
     * Get the district that the ward belongs to.
     */
    public function district(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LocationDistrict::class, 'district_id');
    }
}

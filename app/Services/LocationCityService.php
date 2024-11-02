<?php
namespace App\Services;


use App\Models\LocationCity;

class LocationCityService extends BaseService
{
    public function model(): string
    {
        return LocationCity::class;
    }

    public function addFilter($query, $params): void
    {
        if (isset($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }
    }
}

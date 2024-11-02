<?php
namespace App\Services;


use App\Models\LocationDistrict;

class LocationDistrictService extends BaseService
{
    public function model(): string
    {
        return LocationDistrict::class;
    }

    public function addFilter($query, $params): void
    {
        $query->when(isset($params['city_id']), function ($query) use ($params) {
            $query->where('city_id', $params['city_id']);
        })->when(isset($params['name']), function ($query) use ($params) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        });
    }
}

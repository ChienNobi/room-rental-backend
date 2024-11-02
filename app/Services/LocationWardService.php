<?php
namespace App\Services;


use App\Models\LocationWard;

class LocationWardService extends BaseService
{
    public function model(): string
    {
        return LocationWard::class;
    }

    public function addFilter($query, $params): void
    {
        $query->when(isset($params['district_id']), function ($query) use ($params) {
            $query->where('district_id', $params['district_id']);
        })->when(isset($params['name']), function ($query) use ($params) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        });
    }
}

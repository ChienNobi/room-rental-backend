<?php
namespace App\Services;


use App\Models\User;

class UserService extends BaseService
{

    public function model(): string
    {
        return User::class;
    }

    public function addFilter($query, $params): void
    {
        $query->when(isset($params['name']), function ($query) use ($params) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        })->when(isset($params['is_active']), function ($query) use ($params) {
            $query->where('is_active', $params['is_active']);
        });
    }
}

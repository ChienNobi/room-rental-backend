<?php
namespace App\Services;


use App\Models\Post;

class PostService extends BaseService
{

    public function model(): string
    {
        return Post::class;
    }

    public function addFilter($query, $params): void
    {
        $query->when(isset($params['title']), function ($query) use ($params) {
            $query->where('title', 'like', '%' . $params['title'] . '%');
        })->when(isset($params['status']), function ($query) use ($params) {
            $query->where('status', $params['status']);
        })->when(isset($params['city']), function ($query) use ($params) {
            $query->where('city', $params['city']);
        })->when(isset($params['district']), function ($query) use ($params) {
            $query->where('district', $params['district']);
        })->when(isset($params['ward']), function ($query) use ($params) {
            $query->where('ward', $params['ward']);
        })->when(isset($params['room_type']), function ($query) use ($params) {
            $query->where('room_type', $params['room_type']);
        })->when(isset($params['price']), function ($query) use ($params) {
            $query->whereBetween('rent_fee', $params['price']);
        });
    }
}

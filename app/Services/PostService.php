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
        });
    }
}

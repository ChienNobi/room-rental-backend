<?php
namespace App\Services;


use App\Models\Post;

class PostService extends BaseService
{

    public function model(): string
    {
        return Post::class;
    }
}

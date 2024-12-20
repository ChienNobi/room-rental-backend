<?php
namespace App\Services;


use App\Models\Post;
use App\Models\PostFavorite;

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

    public function getFavoritePost($userId): \Illuminate\Database\Eloquent\Collection|array
    {
        return PostFavorite::query()->join('posts', 'post_favorites.post_id', '=', 'posts.id')
            ->where('post_favorites.user_id', $userId)
            ->select('posts.*')
            ->get();
    }

    public function saveFavoritePost($id, $userId): void
    {
        PostFavorite::query()->create([
            'user_id' => $userId,
            'post_id' => $id
        ]);
    }

    public function deleteFavorite($postId)
    {
        return PostFavorite::query()->where('post_id', $postId)->delete();
    }
}

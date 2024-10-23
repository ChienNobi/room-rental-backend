<?php

namespace App\Services;


use App\Models\Category;

class CategoryService extends BaseService
{

    public function model(): string
    {
        return Category::class;
    }

    public function getCategoryOptions(bool $onlyParent = false): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->model
            ->query()
            ->where('user_id', auth()->id())
            ->when($onlyParent, fn($q) => $q->where('group_id', 0))
            ->get(['id', 'name', 'group_id', 'type']);
    }

    public function getCategoryTree(): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->model->with('children')
            ->where('group_id', 0)
            ->where('user_id', auth()->id())
            ->get();
    }
}

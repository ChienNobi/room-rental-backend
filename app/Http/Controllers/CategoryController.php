<?php

namespace App\Http\Controllers;

use App\Constants\Common;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{

    public function __construct(private readonly CategoryService $service)
    {
    }

    public function index(Request $r)
    {
        return $this->respond($this->service->paginate($r->all()));
    }

    public function getAll()
    {
        return $this->respond($this->service->findAll(['id', 'name', 'type', 'icon', 'report_exclude']));
    }

    public function getCategoryTree()
    {
        return $this->respond($this->service->getCategoryTree());
    }

    public function getCategoryOptions(Request $r)
    {
        return $this->respond($this->service->getCategoryOptions($r->input('only_parent', false)));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $r)
    {
        $data = $r->only(['group_id', 'name', 'type', 'icon', 'report_exclude']);
        $data['icon'] = $data['icon'] ?? Common::CATEGORY_DEFAULT_ICON;
        $data['report_exclude'] = $data['report_exclude'] ?? false;
        $data['user_id'] = Auth::user()->id;
        $data['group_id'] = $data['group_id'] ?? 0;

        return $this->respond($this->service->store($data));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $data = $request->only(['group_id', 'name', 'type', 'icon', 'report_exclude']);
        $data['group_id'] = $data['group_id'] ?? 0;
        $result = $this->service->update(intval($id), $data);
        return $this->respond($result);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\LocationDistrictService;
use Illuminate\Http\Request;

class LocationDistrictController extends Controller
{
    public function __construct(private readonly LocationDistrictService $locationDistrictService)
    {
    }

    public function index(Request $request)
    {
        $relations = $request->input('depth') == 2 ? ['wards'] : [];
        $cities = $this->locationDistrictService->findAll(['*'], $relations);
        return $this->respond($cities);
    }
}

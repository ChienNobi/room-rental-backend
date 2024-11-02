<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\LocationCityService;
use Illuminate\Http\Request;

class LocationCityController extends Controller
{
    public function __construct(private readonly LocationCityService $locationCityService)
    {
    }

    public function index(Request $request)
    {
        $relations = $request->input('depth') == 2 ? ['districts'] : [];
        $cities = $this->locationCityService->findAll(['*'], $relations);
        return $this->respond($cities);
    }
}

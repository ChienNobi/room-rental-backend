<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\LocationDistrictService;
use App\Services\LocationWardService;
use Illuminate\Http\Request;

class LocationWardController extends Controller
{
    public function __construct(private readonly LocationWardService $locationWardService)
    {
    }

    public function index()
    {
        $wards = $this->locationWardService->findAll();
        return $this->respond($wards);
    }
}

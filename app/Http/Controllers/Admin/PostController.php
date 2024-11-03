<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Services\PostService;
use Illuminate\Support\Facades\Log;
use MarcinOrlowski\ResponseBuilder\Exceptions\ArrayWithMixedKeysException;
use MarcinOrlowski\ResponseBuilder\Exceptions\ConfigurationNotFoundException;
use MarcinOrlowski\ResponseBuilder\Exceptions\IncompatibleTypeException;
use MarcinOrlowski\ResponseBuilder\Exceptions\InvalidTypeException;
use MarcinOrlowski\ResponseBuilder\Exceptions\MissingConfigurationKeyException;
use MarcinOrlowski\ResponseBuilder\Exceptions\NotIntegerException;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    public function __construct(protected readonly PostService $postService)
    {
    }


    /**
     * @throws \Exception
     */
    public function store(CreatePostRequest $request): Response
    {
        $result = $this->postService->store($request->only(
            'title', 'description', 'images', 'status',
            'city', 'district', 'ward', 'detail_address', 'lat', 'lon',
            'room_type', 'acreage', 'rent_fee', 'electricity_fee', 'water_fee', 'internet_fee', 'extra_fee',
            'furniture', 'furniture_detail', 'room_number',
            'contact_name', 'contact_email', 'contact_phone'
        ));

        return $this->respond($result);
    }
}

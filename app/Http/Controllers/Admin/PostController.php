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
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
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

    /**
     * @throws InvalidTypeException
     * @throws NotIntegerException
     * @throws ArrayWithMixedKeysException
     * @throws MissingConfigurationKeyException
     * @throws IncompatibleTypeException
     * @throws ConfigurationNotFoundException
     */
    public function update($id, CreatePostRequest $request)
    {
        $result = $this->postService->update(intval($id), $request->only(
            'title', 'description', 'images', 'status',
            'city', 'district', 'ward', 'detail_address', 'lat', 'lon',
            'room_type', 'acreage', 'rent_fee', 'electricity_fee', 'water_fee', 'internet_fee', 'extra_fee',
            'furniture', 'furniture_detail', 'room_number',
            'contact_name', 'contact_email', 'contact_phone'
        ));

        return $this->respond($result);
    }

    /**
     * @throws InvalidTypeException
     * @throws NotFoundExceptionInterface
     * @throws NotIntegerException
     * @throws ContainerExceptionInterface
     * @throws IncompatibleTypeException
     * @throws ConfigurationNotFoundException
     * @throws ArrayWithMixedKeysException
     * @throws MissingConfigurationKeyException
     */
    public function index()
    {
        $posts = $this->postService->paginate();
        return $this->respond($posts);
    }

    public function destroy($id)
    {
        $result = $this->postService->destroy(intval($id));
        return $this->respond($result);
    }

    public function show($id)
    {
        $result = $this->postService->show(intval($id));
        return $this->respond($result);
    }

    public function getFavoritePost()
    {
        Log::info('ChienTT getFavoritePost');
        $result = $this->postService->getFavoritePost(auth()->id());
        return $this->respond($result);
    }

    public function saveFavorite($postId)
    {
        $this->postService->saveFavoritePost($postId, auth()->id());
        return $this->respond(true);
    }

    public function deleteFavorite($postId)
    {
        $this->postService->deleteFavorite($postId);
        return $this->respond(true);
    }
}

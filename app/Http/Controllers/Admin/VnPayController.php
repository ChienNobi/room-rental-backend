<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Services\PostService;
use App\Services\VnPayService;
use Illuminate\Http\Request;
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

class VnPayController extends Controller
{
    public function __construct(protected readonly VnPayService $vnPayService)
    {
    }


    /**
     * @throws InvalidTypeException
     * @throws NotIntegerException
     * @throws ArrayWithMixedKeysException
     * @throws MissingConfigurationKeyException
     * @throws IncompatibleTypeException
     * @throws ConfigurationNotFoundException
     */
    public function buildPaymentUrl(Request $r): Response
    {
        $url = $this->vnPayService->createPayment($r->input('amount'));

        return $this->respond($url);
    }

    /**
     * @throws InvalidTypeException
     * @throws NotIntegerException
     * @throws ArrayWithMixedKeysException
     * @throws MissingConfigurationKeyException
     * @throws ConfigurationNotFoundException
     * @throws IncompatibleTypeException
     */
    public function verifyVnPayCallback(Request $r)
    {
        $result = $this->vnPayService->vnPayCallback($r);
        if(!array_key_exists('Error', $result)) {
            return redirect()->away(config('services.vn_pay.return_url'));
        }

        return redirect()->away(config('services.vn_pay.return_url'));
    }
}

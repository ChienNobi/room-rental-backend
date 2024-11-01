<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Common;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Admin\LoginRequest;
use App\Http\Requests\Admin\Admin\LogoutRequest;
use App\Http\Requests\Admin\Admin\RefreshTokenRequest;
use App\Http\Requests\Admin\Admin\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{
    public function __construct(protected readonly AuthService $authService)
    {
    }

    /**
     * @throws \Exception
     */
    public function create(RegisterRequest $request): Response
    {
        $result = DB::transaction(function() use ($request) {
            return $this->authService->register(User::class, $request->only('name', 'email', 'password', 'avt', 'phone', 'address'));
        });

        return $this->respond($result);
    }

    public function login(LoginRequest $request): \Symfony\Component\HttpFoundation\Response
    {
        $data = $request->only('email', 'password');

        return $this->respond($this->authService->login(User::class, ...array_values($data)));
    }

    public function logout(LogoutRequest $request): Response
    {
        $user = auth()->user();
        $this->authService->revokeToken($user->token());

        return $this->respond();
    }

    public function refreshToken(RefreshTokenRequest $request)
    {
        $data = $request->only('refresh_token');

        $result = $this->authService->refreshToken(Admin::class, $data['refresh_token']);
        if (!$result) {
            throw new UnauthorizedHttpException('', __('api.exception.invalid_refresh_token'));
        }

        return $this->respond($result);
    }

    public function profile()
    {
        $user = auth()->user();

        return $this->respond(['user' => $user]);
    }

    public function activeAccount($token) {
        if(strlen($token) !== Common::REQUEST_ACCOUNT_TOKEN_LENGTH) {
            return $this->respondWithError(Response::HTTP_BAD_REQUEST, Response::HTTP_BAD_REQUEST, "Your link you provided is not valid");
        }
        $validateInfo = $this->authService->checkValidToken($token);
        if(!$validateInfo['is_valid']) {
            return $this->respondWithError(Response::HTTP_BAD_REQUEST, Response::HTTP_BAD_REQUEST, '', $validateInfo);
        }

        return $this->authService->activeUser($validateInfo['data']->user_id, $validateInfo['data']->id);
    }
}


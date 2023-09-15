<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Services\Admin\AuthService;
use App\Traits\GeneralResponseTrait;

class AuthController extends Controller
{
    use GeneralResponseTrait;

    public function __construct(private AuthService $auth_service)
    {
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $admin = $this->auth_service->login($credentials);
        $this->auth_service->createToken($admin);

        return $this->returnData(['admin' => new AdminResource($admin)], 'User Logged in Successfully');
    }

    public function logout()
    {
        $this->auth_service->logout();

        return $this->returnSuccessMessage('User successfully signed out');
    }
}

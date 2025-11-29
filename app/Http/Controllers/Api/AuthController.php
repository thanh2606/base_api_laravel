<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\UpdatePasswordRequest;
use App\Http\Requests\Api\Auth\UpdateProfileRequest;
use App\Http\Requests\Api\Auth\VerifyPasswordRequest;
use App\Http\Resources\Api\User\UserResource;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        protected readonly UserService $userService
    ) {}

    /**
     * @param LoginRequest $request
     * @return UserResource
     * @throws ValidationException
     */
    public function login(LoginRequest $request): UserResource
    {
        $user = $this->userService->login($request);

        return UserResource::make($user);
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->userService->create($request->validated());

        return UserResource::make($user);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        // Registration logic here
    }

    public function verifyPassword(VerifyPasswordRequest $request)
    {
        // Registration logic here
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        // Registration logic here
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        // Registration logic here
    }

    public function logout()
    {
        // Registration logic here
    }

    public function profile(): UserResource
    {
        $user = Auth::user();
        return UserResource::make($user);
    }
}

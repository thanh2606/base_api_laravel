<?php

namespace App\Services\Admin;

use App\Http\Requests\Admin\Auth\LoginRequest;

interface AuthServiceInterface
{
    public function authenticate(LoginRequest $request): void;
}

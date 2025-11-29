<?php

namespace App\Services\User;

use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

interface UserServiceInterface
{
    /**
     * @param string|null $search
     * @param int|null $limit
     * @return LengthAwarePaginator
     */
    public function search(?string $search = null, ?int $limit = null): LengthAwarePaginator;

    /**
     * @param LoginRequest $request
     * @return User
     *
     * @throws ValidationException
     */
    public function login(LoginRequest $request): User;
}

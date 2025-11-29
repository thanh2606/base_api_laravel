<?php

namespace App\Services\User;

use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use App\Repositories\User\UserRepository;
use App\Services\BaseService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserService extends BaseService implements UserServiceInterface
{
    public function __construct(
        protected readonly UserRepository $userRepository
    ) {
        $this->repository = $userRepository;
    }

    /**
     * @param LoginRequest $request
     * @return User
     *
     * @throws ValidationException
     */
    public function login(LoginRequest $request): User
    {
        $email = $request->string('email');
        $ip = $request->ip();
        $key = Str::transliterate(Str::lower($email).'|'.$ip);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            event(new Lockout($request));

            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        $attempt = Auth::attempt(
            credentials: $request->only('email', 'password'),
            remember: $request->boolean('remember')
        );

        if (!$attempt) {
            RateLimiter::hit($key);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        return Auth::user();
    }

    /**
     * @param string|null $search
     * @param int|null $limit
     * @return LengthAwarePaginator
     */
    public function search(?string $search = null, ?int $limit = null): LengthAwarePaginator
    {
        if (empty($search)) {
            return $this->repository
                ->paginate($limit);
        }

        return $this->repository->scopeQuery(function ($query) use ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        })->paginate($limit);
    }
}

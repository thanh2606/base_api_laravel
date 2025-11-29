<?php

namespace App\Services\Admin;

use App\Http\Requests\Admin\Auth\LoginRequest;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceInterface
{
    protected string $email;

    protected string $ip;

    protected string $key;

    /**
     * @throws ValidationException
     */
    public function authenticate(LoginRequest $request): void
    {
        $this->email = $request->string('email');
        $this->ip = $request->ip();
        $this->key = $this->throttleKey($this->email, $this->ip);

        $this->ensureIsNotRateLimited($request);
        $attempt = Auth::guard('admin')->attempt(
            credentials: $request->only('email', 'password'),
            remember: $request->boolean('remember'));

        if (! $attempt) {
            RateLimiter::hit($this->key);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->key);
        $request->session()->regenerate();
    }

    protected function ensureIsNotRateLimited(LoginRequest $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->key, 5)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->key);

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(string $email, string $ip): string
    {
        return Str::transliterate(Str::lower($email).'|'.$ip);
    }
}

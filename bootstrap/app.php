<?php

use App\Http\Middleware\AdminRedirectIfAuthenticated;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Resources\Common\ErrorResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Validation\ValidationException;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);
        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
        $middleware->statefulApi();
        $middleware->api(append: [
            EnsureFrontendRequestsAreStateful::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            'throttle:api',
            SubstituteBindings::class,
        ]);
        $middleware->alias([
            'admin' => AdminRedirectIfAuthenticated::class,
        ]);

        $middleware->redirectTo(function (Request $request) {
            if (!$request->expectsJson()) {
                return route('admin.login');
            }
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }

            return $request->expectsJson();
        });

        $exceptions->render(function (Exception $e, Request $request) {
            if ($request->is('api/*')) {
                if ($e instanceof AuthenticationException) {
                    return (new ErrorResource(
                        message: 'Unauthenticated.',
                        code: Response::HTTP_UNAUTHORIZED,
                    ))->toResponse();
                }

                if (!$e instanceof ValidationException) {
                    return (new ErrorResource(
                        message: $e->getMessage(),
                        code: method_exists($e, 'getStatusCode') ? $e->getStatusCode() : Response::HTTP_BAD_REQUEST,
                    ))->toResponse();
                }
            }
        });

        $exceptions->report(function (Throwable $e) {});
    })
    ->create();

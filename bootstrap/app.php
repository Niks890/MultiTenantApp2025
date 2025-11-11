<?php


use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException;
use Stancl\Tenancy\Exceptions\TenantDatabaseDoesNotExistException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api/admin',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();

        $middleware->web(append: [
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\PreventBackHistory::class,
            \App\Http\Middleware\CheckActiveUser::class,
            \App\Http\Middleware\CheckTenantActive::class,
        ]);

        $middleware->append([]);

        $middleware->alias([
            'auth' => App\Http\Middleware\Authenticate::class,
            'checkActiveUser' => App\Http\Middleware\CheckActiveUser::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->stopIgnoring([
            HttpException::class,
            TokenMismatchException::class,
        ]);
        $exceptions->respond(function (Response $response) {
            if ($response->getStatusCode() === 419) {
                return redirect()->route('admin.login');
            }
            return $response;
        });

        // bat ngoai le khong tim thay tenant
        $exceptions->render(function (TenantCouldNotBeIdentifiedOnDomainException $e) {
            return response()->view('errors.tenant.404', [], 404);
        });

        // bat ngoai le khong tim thay database cua tenant
        $exceptions->render(function (TenantDatabaseDoesNotExistException $e) {
            return response()->view('errors.tenant.404', [], 404);
        });


        $exceptions->render(function (HttpException $e) {
            // Nếu tenant bao tri 503
            if ($e->getStatusCode() === 503) {
                // Nếu tenant bao tri 503, hien thi view 503 cua tenant
                if (function_exists('tenant') && tenant()) {
                    return response()->view('errors.tenant.503', [
                        'tenant' => tenant(),
                    ], 503);
                }
                // Fallback ve view 503 central
                return response()->view('errors.503', [], 503);
            }

                if ($e->getStatusCode() === 404) {
                if (function_exists('tenant') && tenant()) {
                    return response()->view('errors.tenant.404', [
                        'tenant' => tenant(),
                    ], 404);
                }
                // fallback central
                return response()->view('errors.404', [], 404);
            }
        });
    })->create();

<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\File;
use App\Exceptions\InvalidOrderException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware applied to all requests
        $middleware->use([
            \App\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            \App\Http\Middleware\SetDefaultLocaleForUrls::class,
        ]);

        // Web middleware group
        $middleware->group('web', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\I18nMiddleware::class,
            \App\Http\Middleware\SetDefaultCookie::class,
        ]);
        
        // API middleware group
        $middleware->group('api', [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \App\Http\Middleware\I18nMiddleware::class,
        ]);

        // Middleware aliases organized by domain
        $middleware->alias([
            // Authentication core
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

            // Security & rate limiting
            'signed' => \App\Http\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,

            // Admin domain
            'admin.auth' => \App\Http\Middleware\AuthenticateAdmin::class,
            'admin.auth.api' => \App\Http\Middleware\AuthenticateAdminApi::class,
            'admin.role' => \App\Http\Middleware\CheckAdminRole::class,

            // Partner domain
            'partner.auth' => \App\Http\Middleware\AuthenticatePartner::class,
            'partner.auth.api' => \App\Http\Middleware\AuthenticatePartnerApi::class,
            'partner.role' => \App\Http\Middleware\CheckPartnerRole::class,

            // Staff domain
            'staff.auth' => \App\Http\Middleware\AuthenticateStaff::class,
            'staff.role' => \App\Http\Middleware\CheckStaffRole::class,

            // Member domain
            'member.auth' => \App\Http\Middleware\AuthenticateMember::class,
            'member.auth.api' => \App\Http\Middleware\AuthenticateMemberApi::class,
            'member.role' => \App\Http\Middleware\CheckMemberRole::class,

            // Installation checks
            'installed' => \App\Http\Middleware\CheckIfInstalled::class,
            'not.installed' => \App\Http\Middleware\CheckIfNotInstalled::class,

            // Development utilities
            'dummy' => \App\Http\Middleware\Dummy::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Exception reporting configuration
        $exceptions->report(function (InvalidOrderException $e) {
            // Custom reporting logic here
        });

        // Custom exception rendering
        $exceptions->render(function (InvalidOrderException $e, $request) {
            return response("Custom message for InvalidOrderException", 500);
        });

        // Global exception context
        $exceptions->context(fn () => [
            'key' => 'value' // Add global context data here
        ]);

        // Locale handling from URL segment (important for error messages)
        if (!app()->runningInConsole() && $request = request()) {
            $locales = explode('-', $request->segment(1));
            $locale = isset($locales[1]) 
                ? $locales[0].'_'.strtoupper($locales[1])
                : config('app.locale');

            if (!File::exists(lang_path($locale))) {
                $locale = config('app.locale');
            }

            app()->setLocale($locale);
        }

        // Exception logging configuration
        $exceptions->level(InvalidOrderException::class, \Psr\Log\LogLevel::CRITICAL);
        $exceptions->dontReport([
            InvalidOrderException::class,
        ]);
    })
    ->create();
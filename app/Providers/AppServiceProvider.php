<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\URL::forceScheme('https');

        \Livewire\Livewire::setUpdateRoute(function ($handle) {
            $isCentralDomain = in_array(request()->getHost(), config('tenancy.central_domains', []));

            $middlewares = [
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\Session\Middleware\AuthenticateSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \Filament\Http\Middleware\DisableBladeIconComponents::class,
                \Filament\Http\Middleware\DispatchServingFilamentEvent::class,
            ];

            if (! $isCentralDomain) {
                array_unshift(
                    $middlewares,
                    \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
                    \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class
                );
            }

            return \Illuminate\Support\Facades\Route::post('/livewire/update', $handle)
                ->middleware($middlewares)
                ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
        });
    }
}

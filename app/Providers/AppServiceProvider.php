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
        if (env('APP_ENV') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Livewire\Livewire::setUpdateRoute(function ($handle) {
            $isCentralDomain = in_array(request()->getHost(), config('tenancy.central_domains', []));

            $middlewares = ['web'];

            if (! $isCentralDomain) {
                $middlewares[] = \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class;
                $middlewares[] = \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class;
            }

            return \Illuminate\Support\Facades\Route::post('/livewire/update', $handle)
                ->middleware($middlewares);
        });
    }
}

<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\FrontendController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::get('/', [FrontendController::class, 'index'])->name('home');
    Route::get('/kategori/{slug}', [FrontendController::class, 'category'])->name('category');
    Route::get('/haber/{slug}', [FrontendController::class, 'post'])->name('post');

    // Nöbetçi Eczane Dinamik SEO Route (Hem varsayılanı hem de il/ilçe bazlı urlleri yakalar)
    Route::middleware('throttle:30,1')->group(function () {
        Route::get('/{slug}', [\App\Http\Controllers\PharmacyController::class, 'index'])
            ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*-nobetci-eczaneler|nobetci-eczaneler')
            ->name('pharmacy.index');
    });
});

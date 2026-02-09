<?php

declare(strict_types=1);

use App\Http\Controllers\V2\Auth\ForgotPasswordController;
use App\Http\Controllers\V2\Auth\LoginController;
use App\Http\Controllers\V2\Auth\LogoutController;
use App\Http\Controllers\V2\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Rotas do tenant (banco separado por igreja).
| Resolvidas por domínio/subdomínio: {slug}.bethel360.com.br
|
*/

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api/v2')->group(function () {

    // Auth (público)
    Route::post('/auth/login', LoginController::class)->name('auth.login');
    Route::post('/auth/register', RegisterController::class)->name('auth.register');
    Route::post('/auth/forgot-password', ForgotPasswordController::class)->name('auth.forgot-password');

    // Auth (autenticado)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', LogoutController::class)->name('auth.logout');

        // TODO: Adicionar rotas autenticadas (ETAPA 2)
    });

    // Health check
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'tenant' => tenant('id'),
        ]);
    });
});

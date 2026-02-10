<?php

declare(strict_types=1);

use App\Http\Controllers\V2\Auth\ForgotPasswordController;
use App\Http\Controllers\V2\Auth\LoginController;
use App\Http\Controllers\V2\Auth\LogoutController;
use App\Http\Controllers\V2\Auth\RegisterController;
use App\Http\Controllers\V2\CampusController;
use App\Http\Controllers\V2\CampusMinistryController;
use App\Http\Controllers\V2\ChildSafetyController;
use App\Http\Controllers\V2\EnrollmentController;
use App\Http\Controllers\V2\EventController;
use App\Http\Controllers\V2\FamilyLinkController;
use App\Http\Controllers\V2\FeatureController;
use App\Http\Controllers\V2\FinanceController;
use App\Http\Controllers\V2\GroupController;
use App\Http\Controllers\V2\GroupPersonController;
use App\Http\Controllers\V2\MinistryController;
use App\Http\Controllers\V2\MinistryGroupController;
use App\Http\Controllers\V2\PersonController;
use App\Http\Controllers\V2\PresenceController;
use App\Http\Controllers\V2\ServiceAssignmentController;
use App\Http\Controllers\V2\ServiceRequestController;
use App\Http\Controllers\V2\TimelineController;
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

        // =====================================================================
        // ETAPA 2 - CORE Routes
        // =====================================================================

        // CRUD Resources
        Route::apiResource('campuses', CampusController::class);
        Route::apiResource('ministries', MinistryController::class);
        Route::apiResource('groups', GroupController::class);
        Route::apiResource('people', PersonController::class);
        Route::apiResource('events', EventController::class);
        Route::apiResource('features', FeatureController::class)->only(['index', 'show']);

        // Nested Resources
        Route::get('campuses/{campus}/ministries', [CampusMinistryController::class, 'index'])->name('campuses.ministries.index');
        Route::get('ministries/{ministry}/groups', [MinistryGroupController::class, 'index'])->name('ministries.groups.index');
        Route::get('groups/{group}/people', [GroupPersonController::class, 'index'])->name('groups.people.index');
        Route::post('groups/{group}/people', [GroupPersonController::class, 'store'])->name('groups.people.store');
        Route::delete('groups/{group}/people/{person}', [GroupPersonController::class, 'destroy'])->name('groups.people.destroy');

        // Enrollment
        Route::post('enrollment/ministry', [EnrollmentController::class, 'enrollMinistry'])->name('enrollment.ministry');
        Route::post('enrollment/group', [EnrollmentController::class, 'enrollGroup'])->name('enrollment.group');

        // Presence
        Route::post('presence/register', [PresenceController::class, 'register'])->name('presence.register');
        Route::post('presence/batch', [PresenceController::class, 'registerBatch'])->name('presence.batch');

        // Timeline
        Route::get('people/{person}/timeline', [TimelineController::class, 'index'])->name('people.timeline');

        // Family Links
        Route::post('family-links', [FamilyLinkController::class, 'store'])->name('family-links.store');
        Route::patch('family-links/{familyLink}/respond', [FamilyLinkController::class, 'respond'])->name('family-links.respond');

        // Child Safety
        Route::post('child-safety/validate', [ChildSafetyController::class, 'validate'])->name('child-safety.validate');

        // Service Requests
        Route::post('service-requests', [ServiceRequestController::class, 'store'])->name('service-requests.store');
        Route::patch('service-requests/{serviceRequest}/respond', [ServiceRequestController::class, 'respond'])->name('service-requests.respond');

        // Service Assignments
        Route::post('service-assignments', [ServiceAssignmentController::class, 'store'])->name('service-assignments.store');
        Route::patch('service-assignments/{serviceAssignment}/respond', [ServiceAssignmentController::class, 'respond'])->name('service-assignments.respond');

        // Finances
        Route::post('finances', [FinanceController::class, 'store'])->name('finances.store');
        Route::get('people/{person}/finances', [FinanceController::class, 'history'])->name('people.finances');

        // Restore soft-deleted records
        Route::post('campuses/{id}/restore', [CampusController::class, 'restore'])->name('campuses.restore');
        Route::post('ministries/{id}/restore', [MinistryController::class, 'restore'])->name('ministries.restore');
        Route::post('groups/{id}/restore', [GroupController::class, 'restore'])->name('groups.restore');
        Route::post('people/{id}/restore', [PersonController::class, 'restore'])->name('people.restore');
    });

    // Health check
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'tenant' => tenant('id'),
        ]);
    });
});

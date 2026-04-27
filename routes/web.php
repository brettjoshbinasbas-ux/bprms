<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Resident\DashboardController as ResidentDashboard;
use App\Http\Controllers\Resident\PremisesController as ResidentPremisesController;
use App\Http\Controllers\Resident\ApplicationController as ResidentApplicationController;
use App\Http\Controllers\Resident\PaymentController;
use App\Http\Controllers\Resident\NotificationController as ResidentNotificationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PremisesController as AdminPremisesController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\AgreementController;
use App\Http\Controllers\Admin\ResidentController as AdminResidentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;

// Redirect root to login
Route::get('/', fn() => redirect()->route('login'));

// ============================================================
// GUEST ROUTES
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Logout (handles both guards)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================================
// RESIDENT ROUTES
// ============================================================
Route::middleware('resident')
    ->prefix('resident')
    ->name('resident.')
    ->group(function () {
        Route::get('/dashboard', [ResidentDashboard::class, 'index'])->name('dashboard');

        // Browse premises
        Route::prefix('premises')
            ->name('premises.')
            ->group(function () {
                Route::get('/', [ResidentPremisesController::class, 'index'])->name('index');
                Route::get('/{premises}', [ResidentPremisesController::class, 'show'])->name('show');
            });

        // Applications
        Route::prefix('applications')
            ->name('applications.')
            ->group(function () {
                Route::get('/', [ResidentApplicationController::class, 'index'])->name('index');
                Route::get('/create', [ResidentApplicationController::class, 'create'])->name('create');
                Route::post('/', [ResidentApplicationController::class, 'store'])->name('store');
                Route::get('/{id}', [ResidentApplicationController::class, 'show'])->name('show');
                Route::post('/{id}/cancel', [ResidentApplicationController::class, 'cancel'])->name('cancel');
            });

        // Payment
        Route::prefix('payment')
            ->name('payment.')
            ->group(function () {
                Route::get('/{applicationId}', [PaymentController::class, 'showPayment'])->name('form');
                Route::post('/process', [PaymentController::class, 'processPayment'])->name('process');
                Route::get('/confirm/{paymentId}', [PaymentController::class, 'confirm'])->name('confirm');
            });

        // Notifications
        Route::prefix('notifications')
            ->name('notifications.')
            ->group(function () {
                Route::get('/', [ResidentNotificationController::class, 'index'])->name('index');
                Route::post('/{id}/mark-read', [ResidentNotificationController::class, 'markRead'])->name('markRead');
                Route::post('/mark-all-read', [ResidentNotificationController::class, 'markAllRead'])->name('markAllRead');
            });
    });

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::middleware(['admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Locations
        Route::prefix('locations')
            ->name('locations.')
            ->group(function () {
                Route::get('/', [LocationController::class, 'index'])->name('index');
                Route::post('/', [LocationController::class, 'store'])->name('store');
                Route::put('/{location}', [LocationController::class, 'update'])->name('update');
                Route::delete('/{location}', [LocationController::class, 'destroy'])->name('destroy');
            });

        // Premises
        Route::prefix('premises')
            ->name('premises.')
            ->group(function () {
                Route::get('/', [AdminPremisesController::class, 'index'])->name('index');
                Route::post('/', [AdminPremisesController::class, 'store'])->name('store');
                Route::put('/{premises}', [AdminPremisesController::class, 'update'])->name('update');
                Route::delete('/{premises}', [AdminPremisesController::class, 'destroy'])->name('destroy');
            });

        // Applications
        Route::prefix('applications')
            ->name('applications.')
            ->group(function () {
                Route::get('/', [AdminApplicationController::class, 'index'])->name('index');
                Route::get('/{id}', [AdminApplicationController::class, 'show'])->name('show');
                Route::post('/{id}/review', [AdminApplicationController::class, 'review'])->name('review');
            });

        // Rental Agreements
        Route::prefix('agreements')
            ->name('agreements.')
            ->group(function () {
                Route::get('/', [AgreementController::class, 'index'])->name('index');
                Route::get('/{id}', [AgreementController::class, 'show'])->name('show');
                Route::post('/{id}/terminate', [AgreementController::class, 'terminate'])->name('terminate');
            });

        // Residents (view-only for admin)
        Route::prefix('residents')
            ->name('residents.')
            ->group(function () {
                Route::get('/', [AdminResidentController::class, 'index'])->name('index');
                Route::get('/{resident}', [AdminResidentController::class, 'show'])->name('show');
            });

        // Reports
        Route::prefix('reports')
            ->name('reports.')
            ->group(function () {
                Route::get('/active-agreements', [ReportController::class, 'activeAgreements'])->name('active-agreements');
                Route::get('/revenue', [ReportController::class, 'revenueSummary'])->name('revenue');
                Route::get('/applications', [ReportController::class, 'applicationStats'])->name('applications');
                Route::get('/occupancy', [ReportController::class, 'occupancy'])->name('occupancy');
            });

        // Announcements (Admin Notifications)
        Route::prefix('notifications')
            ->name('notifications.')
            ->group(function () {
                Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
                Route::post('/', [AdminNotificationController::class, 'store'])->name('store');
                Route::delete('/{id}', [AdminNotificationController::class, 'destroy'])->name('destroy');
            });
    });

<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ReportMessageController;
use App\Http\Controllers\ReportPhotoController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\TrackingController;
use App\Http\Middleware\SetPublicLocale;
use Illuminate\Support\Facades\Route;

Route::get('/up', function () {
    return response()->json(['status' => 'ok']);
});

Route::post('/language', [LocaleController::class, 'update'])->name('locale.update');

Route::get('/', function () {
    return view('landing');
})->middleware(SetPublicLocale::class)->name('landing');

Route::view('/report', 'public.report')->middleware(SetPublicLocale::class)->name('public.report');
Route::middleware([SetPublicLocale::class, 'throttle:10,1'])->group(function () {
    Route::get('/track', [TrackingController::class, 'show'])->name('track');
    Route::get('/track/{token}', [TrackingController::class, 'showByToken'])->name('track.token');
    Route::get('/track/{token}/photo', [ReportPhotoController::class, 'forReporter'])->name('track.photo');
    Route::get('/track/{token}/messages', [ReportMessageController::class, 'reporterIndex'])->name('track.messages');
    Route::post('/track/{token}/messages', [ReportMessageController::class, 'reporterStore'])->name('track.messages.store');
});

Route::get('/staff/login', [StaffAuthController::class, 'showLoginForm'])->name('staff.login');
Route::post('/staff/login', [StaffAuthController::class, 'login']);
Route::post('/staff/logout', [StaffAuthController::class, 'logout'])->name('staff.logout');

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:staff,admin', 'throttle:20,1'])->group(function () {
        Route::get('/staff/dashboard', [StaffDashboardController::class, 'index'])->name('staff.dashboard');
        Route::get('/staff/dashboard/poll', [StaffDashboardController::class, 'poll'])->name('staff.dashboard.poll');
        Route::post('/staff/reports/{report}/triage', [StaffDashboardController::class, 'triage'])->name('staff.report.triage');
        Route::get('/staff/reports/{report}/messages', [ReportMessageController::class, 'staffIndex'])->name('staff.report.messages');
        Route::post('/staff/reports/{report}/messages', [ReportMessageController::class, 'staffStore'])->name('staff.report.messages.store');
        Route::get('/staff/reports/{report}/photo', [ReportPhotoController::class, 'forStaff'])->name('staff.report.photo');
    });

    Route::middleware('role:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/queue', [AdminDashboardController::class, 'queue'])->name('admin.dashboard.queue');
    });
});

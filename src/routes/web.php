<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ParticipantPortalController;
use App\Http\Middleware\ParticipantAuth;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/

// Language switcher
Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return back();
})->name('lang.switch');

// Public pages with locale middleware
Route::middleware([SetLocale::class])->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/programs', [PageController::class, 'programs'])->name('programs');
    Route::get('/events', [PageController::class, 'events'])->name('events');
    Route::get('/our-partner', [PageController::class, 'ourPartner'])->name('our-partner');

    // Event Registration
    Route::get('/events/{event}/register', [EventRegistrationController::class, 'show'])->name('event.register');
    Route::post('/events/{event}/register', [EventRegistrationController::class, 'store'])->name('event.register.store');
    Route::get('/events/{event}/register/success', [EventRegistrationController::class, 'success'])->name('event.register.success');
    Route::get('/captcha/refresh', [EventRegistrationController::class, 'refreshCaptcha'])->name('captcha.refresh');
});

Route::get('/resources', function () {
    return view('pages.resources');
})->name('resources');

// Public Certificate Verification
Route::get('/verify/{code}', [CertificateController::class, 'verify'])->name('certificate.verify');

// Admin Certificate Preview
Route::get('/admin/certificate/{registration}/preview', [CertificateController::class, 'adminPreview'])
    ->name('admin.certificate.preview')
    ->middleware('auth');

// Admin Verify Attendance (QR scan)
Route::get('/admin/verify-attendance/{code}', [AttendanceController::class, 'show'])
    ->name('admin.verify-attendance')
    ->middleware('auth');
Route::post('/admin/verify-attendance/{code}', [AttendanceController::class, 'store'])
    ->name('admin.verify-attendance.store')
    ->middleware('auth');

// Participant Portal
Route::middleware([SetLocale::class])->group(function () {
    Route::get('/portal/login', [ParticipantPortalController::class, 'loginForm'])->name('participant.login');
    Route::post('/portal/login', [ParticipantPortalController::class, 'login'])->name('participant.login.store');
    Route::post('/portal/logout', [ParticipantPortalController::class, 'logout'])->name('participant.logout');
    Route::get('/portal/captcha/refresh', [ParticipantPortalController::class, 'refreshCaptcha'])->name('captcha.refresh.portal');

    Route::middleware([ParticipantAuth::class])->prefix('portal')->group(function () {
        Route::get('/set-password', [ParticipantPortalController::class, 'setPasswordForm'])->name('participant.set-password');
        Route::post('/set-password', [ParticipantPortalController::class, 'setPassword'])->name('participant.set-password.store');
        Route::get('/', [ParticipantPortalController::class, 'dashboard'])->name('participant.dashboard');
        Route::get('/event/{registration}', [ParticipantPortalController::class, 'eventDetail'])->name('participant.event');
        Route::get('/event/{registration}/certificate', [CertificateController::class, 'download'])->name('participant.certificate');
        Route::get('/event/{registration}/certificate/preview', [CertificateController::class, 'preview'])->name('participant.certificate.preview');
        Route::post('/event/{registration}/verify-attendance', [ParticipantPortalController::class, 'verifyAttendance'])->name('participant.verify-attendance');
    });
});

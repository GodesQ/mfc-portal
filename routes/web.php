<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventAttendanceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymayaController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TithesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::middleware(['guest', 'nocache'])->group(function () {
    Route::redirect('/', '/login');
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::get('/forgot-password', [AuthenticatedSessionController::class, 'reset_password'])->name('password.update');
});

Route::get('/events/show/{identifier}', [EventController::class, 'show'])->name('events.show');

Route::middleware(['auth', 'verified'])->group(function () {
    //Language Translation
    Route::get('/index/{locale}', [HomeController::class, 'lang']);
    Route::get('/', [HomeController::class, 'root'])->name('root');

    Route::resource('/dashboards', DashboardController::class)->middleware(['auth', 'verified', 'nocache']);

    Route::prefix('dashboard')->middleware(['auth', 'verified', 'checkSession'])->group(function () {
        Route::resource('/announcements', AnnouncementController::class);

        Route::get('/users/search', [UserController::class, 'search'])->name('search');
        Route::get('/users/profile/{user_id}');
        Route::resource('/users', UserController::class)->except(['index']);
        
        Route::get('/directory/{section}', [UserController::class, 'index'])->name('users.index');

        Route::get('/profile/{user}', [UserController::class, 'profile'])->name('users.profile');
        Route::put('/profile/update/{user}', [UserController::class, 'updateProfile'])->name('users.profile.update');
        Route::put('/profile/services/{user}', [UserController::class, 'updateProfileService'])->name('users.profile.services.put');
        Route::put('/profile/change-password/{user}', [UserController::class, 'updatePassword'])->name('users.profile.change_password');

        Route::get('/events/calendar', [EventController::class, 'calendar'])->name('events.calendar');
        Route::get('events/all', [EventController::class, 'all'])->name('events.all');
        Route::get('/events/full-calendar', [EventController::class, 'fullCalendar'])->name('events.c');
        Route::resource('/events', EventController::class)->except(['show']);
        Route::get('/events/{event_id}/register', [EventRegistrationController::class, 'register'])->name('events.register');
        Route::post('/events/register', [EventRegistrationController::class, 'save_registration'])->name('events.register.post');
        
        Route::resource('/tithes', TithesController::class);
        Route::post('attendances/save', [EventAttendanceController::class, 'saveAttendance'])->name('attendances.save');
        Route::get('attendances/events/{event_id}/users', [EventAttendanceController::class, 'getEventUsers'])->name('attendances.users');
        Route::get('attendances/report/{event_id}', [EventAttendanceController::class, 'report'])->name('attendances.report');

        Route::resource('roles', RolesController::class);

        Route::resource('permissions', PermissionsController::class);

        //Update User Details
        Route::post('/update-password/{id}', [HomeController::class, 'updatePassword'])->name('updatePassword');
    });
});

Route::post('/paymaya/webhook/checkout-success', [PaymayaController::class, 'checkout_success']);
Route::post('/paymaya/webhook/checkout-failed', [PaymayaController::class, 'checkout_failed']);
Route::post('/paymaya/webhook/payment-success', [PaymayaController::class, 'payment_success']);
Route::post('/paymaya/webhook/payment-failed', [PaymayaController::class, 'checkout_failed']);

Route::get('/payments/success', [RedirectController::class, 'payment_success']);

Route::fallback( function() {
    return redirect()->route('root');
});

require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventAttendanceController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymayaController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TitheController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Pusher\PushNotifications\PushNotifications;

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

Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot-password.post');
Route::get('password-reset', [ResetPasswordController::class, 'viewResetPassword']);
Route::post('password-reset', [ResetPasswordController::class, 'reset'])->name('password-reset.post');

// Route::get('/test-notify/{userId}', function ($userId) {
//     $beamsClient = new PushNotifications([
//         'instanceId' => config('broadcasting.connections.pusher.options.beams_instance_id'),
//         'secretKey' => config('broadcasting.connections.pusher.options.beams_secret_key'),
//     ]);
//     $interests = ["user-{$userId}-tithe-reminder"];
//     $beamsClient->publishToInterests(
//         $interests,

//     );
//     return response()->json(['status' => 'sent']);
// });

Route::get('/events/show/{identifier}', [EventsController::class, 'show'])->name('events.show');

Route::middleware(['auth', 'verified'])->group(function () {
    //Language Translation
    Route::get('/index/{locale}', [HomeController::class, 'lang']);
    Route::get('/', [HomeController::class, 'root'])->name('root');

    Route::resource('/dashboards', DashboardController::class)->middleware(['auth', 'verified', 'nocache']);

    Route::get('system-server-logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

    Route::prefix('dashboard')->middleware(['auth', 'verified', 'checkSession'])->group(function () {
        Route::resource('/announcements', AnnouncementController::class);

        Route::get('/users/search', [UsersController::class, 'search'])->name('search');
        // Route::get('/users/profile/{user_id}');
        Route::resource('/users', UsersController::class)->except(['index', 'destroy']);

        Route::delete('/directory/{user_id}/users', [UsersController::class, 'destroy'])->name('users.destroy');
        Route::get('/directory/{section}', [UsersController::class, 'index'])->name('users.index');

        Route::get('/profile/{user}', [UsersController::class, 'profile'])->name('users.profile');
        Route::put('/profile/update/{user}', [UsersController::class, 'updateProfile'])->name('users.profile.update');
        Route::put('/profile/services/{user}', [UsersController::class, 'updateProfileService'])->name('users.profile.services.put');
        Route::put('/profile/change-password/{user}', [UsersController::class, 'updatePassword'])->name('users.profile.change_password');
        Route::put('profile/upload-avatar/{user}', [UsersController::class, 'uploadProfileImage'])->name('users.profile.upload_avatar');

        Route::get('events/calendar', [EventsController::class, 'calendar'])->name('events.calendar');
        Route::get('events/all', [EventsController::class, 'all'])->name('events.all');
        Route::get('events/full-calendar', [EventsController::class, 'fullCalendar'])->name('events.full_calendar');
        Route::resource('/events', EventsController::class)->except(['show']);

        Route::get('/events/registrations/{id}', [EventRegistrationController::class, 'show'])->name('events.registrations.show');
        Route::get('/events/{event}/registrations', [EventRegistrationController::class, 'list'])->name('events.registrations.index');
        Route::get('/events/{event_id}/register', [EventRegistrationController::class, 'register'])->name('events.register');
        Route::post('/events/register', [EventRegistrationController::class, 'save_registration'])->name('events.register.post');
        Route::get('/users/{user_id}/events/registrations', [EventRegistrationController::class, 'userRegistrations'])->name('users.events.registrations');

        Route::resource('/tithes', TitheController::class);
        Route::get('tithes/chart/user-monthly', [TitheController::class, 'userMonthlyTithes'])->name('tithes.chart.user-monthly');

        Route::get('attendances', [EventAttendanceController::class, 'index'])->name('attendances.index');
        Route::post('attendances/users', [EventAttendanceController::class, 'storeUser'])->name('attendances.users.store');
        Route::post('attendances/save', [EventAttendanceController::class, 'saveAttendance'])->name('attendances.save');
        Route::get('attendances/events/{event_id}/users', [EventAttendanceController::class, 'getEventUsers'])->name('attendances.users');
        Route::get('attendances/report/{event_id}', [EventAttendanceController::class, 'report'])->name('attendances.report');

        Route::get('transactions/{transaction}/show', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');

        Route::resource('roles', RolesController::class);

        Route::resource('permissions', PermissionsController::class);

        //Update User Details
        Route::post('/update-password/{id}', [HomeController::class, 'updatePassword'])->name('updatePassword');
    });
});


// Route::fallback(function () {
//     return redirect()->route('root');
// });

Route::post('payments/webhook', [WebhookController::class, 'webhook']);

Route::group(['prefix' => 'redirect'], function () {
    Route::group(['prefix' => 'payment'], function () {
        Route::get('success', [RedirectController::class, 'payment_success'])->name('payments.success');
        Route::get('failed', [RedirectController::class, 'payment_failed'])->name('payments.failed');
        Route::get('cancelled', [RedirectController::class, 'payment_canceled'])->name('payments.cancelled');
    });


});

require __DIR__ . '/auth.php';

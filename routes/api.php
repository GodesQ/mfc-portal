<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventAttendancesController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TitheController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\PaymayaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
  return "";
});

Route::prefix('v1')->group(function (): void {
  Route::post('login', [AuthController::class, 'login']);
  Route::post('register', [AuthController::class, 'register']);

  Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);

  Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('otp/resend', [AuthController::class, 'resendOTP']);
    Route::post('otp/verify', [AuthController::class, 'verifyOTP']);

    Route::put('profile', [ProfileController::class, 'updateProfile']);

    Route::get('users/{user_id}/tithes', [TitheController::class, 'userTithes']);
    Route::get('tithes', [TitheController::class, 'index']);
    Route::post('tithes', [TitheController::class, 'store']);

    Route::post('payments/link', [PaymayaController::class, 'generatePaymentLink']);

    Route::put('transactions/{id}/update-status', [TransactionController::class, 'updateStatus']);

    Route::get('events', [EventController::class, 'index']);

    Route::get('users/{user_id}/notifications', [NotificationController::class, 'getUserNotifications']);
    Route::patch('notifications/{id}/read', [NotificationController::class, 'updateReadStatus']);
    Route::post('notifications/register-device', [NotificationController::class, 'registerDevice']);
  });
});


Route::post('events/attendances', [EventAttendancesController::class, 'saveAttendance']);

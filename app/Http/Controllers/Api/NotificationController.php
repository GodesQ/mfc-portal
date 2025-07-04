<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Services\ExceptionHandlerService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
  public function getUserNotifications(Request $request)
  {
    $user = Auth::user();
    $notifications = $user->notifications;

    return response()->json([
      'status' => 'success',
      'message' => 'User Notifications Retrieved',
      'notifications' => NotificationResource::collection($notifications),
    ]);
  }

  public function updateReadStatus(Request $request)
  {
    try {
      $notification = Notification::where('id', $request->id)->first();

      $notification->update([
        'read_at' => Carbon::now(),
      ]);

      return response()->json([
        'status' => 'success',
        'message' => 'Read Notification Successfully'
      ]);

    } catch (Exception $exception) {
      $exceptionService = new ExceptionHandlerService();
      return $exceptionService->handler($request, $exception);
    }
  }
}

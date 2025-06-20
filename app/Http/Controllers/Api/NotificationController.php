<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
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
}

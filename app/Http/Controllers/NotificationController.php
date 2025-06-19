<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use NotificationChannels\PusherPushNotifications\PusherBeam;
use Pusher\PushNotifications\PushNotifications;

class NotificationController extends Controller
{
    public function registerDevice(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'platform' => 'required|in:ios,android'
        ]);

        $user = $request->user();

        $beamsClient = new PushNotifications([
            'instanceId' => config('broadcasting.connections.pusher.options.beams_instance_id'),
            'secretKey' => config('broadcasting.connections.pusher.options.beams_secret_key'),
        ]);

        try {
            if ($request->platform === 'ios') {
                $beamsClient->publishToInterests(
                    ["user-{$user->id}-ios"],
                    [
                        'apns' => [
                            'aps' => [
                                'alert' => [
                                    'title' => 'Tithe Reminder',
                                    'body' => 'You haven\'t submitted your tithe for this month yet.',
                                ],
                            ],
                        ],
                    ]
                );
            } else {
                $beamsClient->publishToInterests(
                    ["user-{$user->id}-android"],
                    [
                        'fcm' => [
                            'notification' => [
                                'title' => 'Tithe Reminder',
                                'body' => 'You haven\'t submitted your tithe for this month yet.',
                            ],
                        ],
                    ]
                );
            }

            return response()->json(['status' => 'success', 'message' => 'Device registered successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getNotifications(Request $request)
    {
        $notifications = $request->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => 'Tithe Reminder',
                    'body' => $notification->data['title'] ?? 'You haven\'t submitted your tithe for this month yet.',
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->toDateTimeString(),
                ];
            });

        return response()->json($notifications);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use NotificationChannels\PusherPushNotifications\PusherBeam;
use Pusher\PushNotifications\PushNotifications;
use Illuminate\Support\Facades\Http;


class NotificationController extends Controller
{
  public function sendNotification(Request $request)
  {
    // Validate the request
    $request->validate([
      'user_id' => 'required|exists:users,id',
      'title' => 'sometimes|string',
      'message' => 'sometimes|string',
      'type' => 'nullable|string',
    ]);

    // Get the user
    $user = User::find($request->user_id);

    // Prepare the interest name
    $interest = "user-{$user->id}-tithe-reminder";

    // Get Pusher Beams credentials
    $instanceId = config('broadcasting.connections.pusher.options.beams_instance_id');
    $secretKey = config('broadcasting.connections.pusher.options.beams_secret_key');
    $endpoint = "https://{$instanceId}.pushnotifications.pusher.com/publish_api/v1/instances/{$instanceId}/publishes";

    // Customize the notification message if provided
    $title = $request->title ?? 'Tithe Reminder';
    $body = $request->message ?? "Dear {$user->first_name}, you haven't submitted this month's tithe";
    $type = $request->type ?? 'tithe_reminder';

    try {
      $payload = [
        'interests' => [$interest],
        'web' => [
          'notification' => [
            'title' => $title,
            'body' => $body,
          ],
          'data' => [
            'type' => $type,
            'user_id' => $user->id,
          ]
        ],
        'apns' => [
          'aps' => [
            'alert' => [
              'title' => $title,
              'body' => $body,
            ],
            'sound' => 'default',
            'badge' => 1,
          ],
          'data' => [
            'type' => $type,
            'user_id' => $user->id,
          ]
        ],
        'fcm' => [
          'notification' => [
            'title' => $title,
            'body' => $body,
          ],
          'data' => [
            'type' => $type,
            'user_id' => $user->id,
          ]
        ]
      ];

      $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $secretKey
      ])->post($endpoint, $payload);

      if ($response->failed()) {
        $error = $response->json('error', 'HTTP request failed with status ' . $response->status());
        throw new Exception($error);
      }

      $this->storeNotification($user, $payload);

      return response()->json([
        'success' => true,
        'message' => "Notification sent successfully to user {$user->id}",
        'interest' => $interest,
        'payload' => $payload
      ]);

    } catch (Exception $e) {
      Log::error($e->getMessage(), [$e]);

      return response()->json([
        'success' => false,
        'message' => "Failed to send notification: {$e->getMessage()}",
        'error' => $e->getMessage()
      ], 500);
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

  protected function storeNotification($user, $payload)
  {
    $user->notifications()->create([
      'type' => 'tithe_reminder',
      'notifiable_type' => User::class,
      'notifiable_id' => $user->id,
      'data' => json_encode([
        'title' => $payload['web']['notification']['title'],
        'body' => $payload['web']['notification']['body'],
        'user_id' => $payload['web']['data']['user_id'],
        'created_at' => now()->toDateTimeString(),
      ]),
      'read_at' => null,
    ]);
  }
}

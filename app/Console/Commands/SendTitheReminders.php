<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SendTitheReminders extends Command
{
    protected $signature = 'tithe:send-reminders';
    protected $description = 'Send monthly tithe reminders to users';

    public function handle()
    {
        // Get users who haven't tithed this month
        $currentMonthName = now()->englishMonth; // Gets current month name (e.g. "July")

        $users = User::whereDoesntHave('tithes', function ($query) use ($currentMonthName) {
            $query->where('for_the_month_of', $currentMonthName)
                ->whereYear('created_at', now()->year);
        })
            ->get();

        foreach ($users as $user) {
            $this->sendReminder($user);
        }

        $this->info("Sent reminders to {$users->count()} users");
    }

    protected function sendReminder($user)
    {
        // $interest = "debug-mfc-app";
        $interest = "user-{$user->id}-tithe-reminder";
        $instanceId = config('broadcasting.connections.pusher.options.beams_instance_id');
        $secretKey = config('broadcasting.connections.pusher.options.beams_secret_key');
        $endpoint = "https://{$instanceId}.pushnotifications.pusher.com/publish_api/v1/instances/{$instanceId}/publishes";

        try {
            $payload = [
                'interests' => [$interest],
                'web' => [
                    'notification' => [
                        'title' => 'Tithe Reminder',
                        'body' => "Dear {$user->first_name}, you haven't submitted this month's tithe",
                    ],
                    'data' => [
                        'type' => 'tithe_reminder',
                        'user_id' => $user->id,
                    ]
                ],
                'apns' => [
                    'aps' => [
                        'alert' => [
                            'title' => 'Tithe Reminder',
                            'body' => "Dear {$user->first_name}, you haven't submitted this month's tithe",
                        ],
                        'sound' => 'default',
                        'badge' => 1,
                    ],
                    'data' => [
                        'type' => 'tithe_reminder',
                        'user_id' => $user->id,
                    ]
                ],
                'fcm' => [
                    'notification' => [
                        'title' => 'Tithe Reminder',
                        'body' => "Dear {$user->first_name}, you haven't submitted this month's tithe",
                    ],
                    'data' => [
                        'type' => 'tithe_reminder',
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
                throw new \Exception($error);
            }

            $this->storeNotification($user, $payload);

            $this->info("Notified user {$user->id} via interest: {$interest}");
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [$e]);
            if (str_contains($e->getMessage(), 'no devices')) {
                $this->warn("User {$user->id} has no devices registered");
            } else {
                $this->error("Failed to notify user {$user->id}: {$e->getMessage()}");
            }
        }
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
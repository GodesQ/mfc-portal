<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendTitheReminders extends Command
{
    protected $signature = 'tithe:send-reminders';
    protected $description = 'Send monthly tithe reminders to users';

    public function handle()
    {
        // Get users who haven't tithed this month
        $currentMonthName = now()->englishMonth; // Gets current month name (e.g. "July")

        // $users = User::whereDoesntHave('tithes', function ($query) use ($currentMonthName) {
        //     $query->where('for_the_month_of', $currentMonthName)
        //         ->whereYear('created_at', now()->year);
        // })
        //     ->get();

        // foreach ($users as $user) {

        // }


        $user = User::find(6);
        $this->sendReminder($user);

        $this->info("Sent reminders to {$user->count()} users");
    }

    protected function sendReminder($user)
    {
        $interest = "debug-mfc-app";
        $instanceId = config('broadcasting.connections.pusher.options.beams_instance_id');
        $secretKey = config('broadcasting.connections.pusher.options.beams_secret_key');
        $endpoint = "https://{$instanceId}.pushnotifications.pusher.com/publish_api/v1/instances/{$instanceId}/publishes";

        try {
            $payload = json_encode([
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
            ]);

            $ch = curl_init($endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $secretKey
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($response === false) {
                throw new \Exception(curl_error($ch));
            }

            curl_close($ch);

            if ($httpCode >= 400) {
                $error = json_decode($response, true);
                throw new \Exception($error['error'] ?? 'HTTP request failed with status ' . $httpCode);
            }

            $this->info("Notified user {$user->id} via interest: {$interest}");
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'no devices')) {
                $this->warn("User {$user->id} has no devices registered");
            } else {
                $this->error("Failed to notify user {$user->id}: {$e->getMessage()}");
            }
        }
    }
}

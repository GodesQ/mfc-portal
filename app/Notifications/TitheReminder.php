<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\PusherPushNotifications\PusherChannel;
use NotificationChannels\PusherPushNotifications\PusherMessage;

class TitheReminder extends Notification
{
    use Queueable;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return [PusherChannel::class, 'mail', 'database'];
    }

    public function toPush($notifiable)
    {
        return PusherMessage::create()
            ->android()
            ->title('Monthly Tithe Reminder')
            ->body("You haven't submitted your tithe for this month.")
            ->withWeb(
                PusherMessage::create()
                    ->title("Monthly Tithe Reminder")
                    ->body("You haven't submitted your tithe for this month.")
            );
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Monthly Tithe Reminder')
            ->line("Dear {$this->user->name},")
            ->line("You haven't submitted your tithe for this month.")
            ->action('Submit Tithe Now', url('/tithe'))
            ->line('Thank you for your continued support!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'tithe_reminder',
            'user_id' => $this->user->id,
            'message' => "Dear {$this->user->name}, you haven't submitted this month's tithe",
            'link' => url('/tithe'),
        ];
    }
}

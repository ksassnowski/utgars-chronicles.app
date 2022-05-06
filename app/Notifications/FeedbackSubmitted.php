<?php

declare(strict_types=1);

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeedbackSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public User $user;

    public string $message;

    public function __construct(User $user, string $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('New Feedback Submitted')
            ->markdown('mail.feedback', [
                'user' => $this->user,
                'message' => $this->message,
            ]);
    }
}

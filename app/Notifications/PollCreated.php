<?php

namespace App\Notifications;

use App\Models\Poll;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PollCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Poll $poll
    ) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $hasExpirationDate = isset($this->poll->expires_at);

        return (new MailMessage)
            ->from('team@pollster.com', 'Pollster Team')
            ->greeting('Hello!')
            ->line('Your poll was created successfully.')
            ->line('You can share it with this {{ link }}.')
            ->lineIf(
                $hasExpirationDate,
                'It will remain open until '
                . (new \DateTime($this->poll->expires_at))->format('m/d/Y')
                . '.'
            );
    }
}

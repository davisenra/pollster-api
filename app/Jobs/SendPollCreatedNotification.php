<?php

namespace App\Jobs;

use App\Models\Poll;
use App\Notifications\PollCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendPollCreatedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly Poll $poll
    ) {}

    public function handle(): void
    {
        Notification::route('mail', [$this->poll->email => 'User'])
            ->notify(new PollCreated($this->poll));
    }
}

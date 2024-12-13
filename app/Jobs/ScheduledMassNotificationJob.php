<?php

namespace App\Jobs;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ScheduledMassNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected string $title, protected string $message, protected ?array $users_id = null)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->users_id !== null) {

            Notification::make()
                ->title($this->title)
                ->success()
                ->body($this->message)
                ->send(User::find($this->users_id));
            return;
        }

        User::chunk(100, function ($users) {
            Notification::make()
                ->title($this->title)
                ->success()
                ->body($this->message)
                ->sendToDatabase($users);
        });
    }
}

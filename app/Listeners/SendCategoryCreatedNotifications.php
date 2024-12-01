<?php

namespace App\Listeners;

use App\Events\CategoryCreated;
use App\Models\User;
use App\Notifications\CreateCategory;
use App\Notifications\NewCategory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCategoryCreatedNotifications implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CategoryCreated $event): void
    {
        // foreach (User::whereNot('id', $event->chirp->user_id)->cursor() as $user) {
        //     $user->notify(new NewChirp($event->chirp));
        // }
        $admin = User::where('role', 'admin')->first();
        $admin->notify(new NewCategory($event->category));
    }
}

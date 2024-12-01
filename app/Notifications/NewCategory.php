<?php

namespace App\Notifications;

use App\Models\Category;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCategory extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Category $category)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->line('Category created: ' . $this->category->name)
            ->action('Notification Action', url('/admin/categories'))
            ->line('Thank you for using our application!');
    }

    // public function toDatabase(User $notifiable): array
    // {
    //     return FilamentNotification::make()
    //         ->title('Saved successfully')
    //         ->getDatabaseMessage();
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [];
        // return FilamentNotification::make()
        //     ->title('Saved successfully')
        //     ->getDatabaseMessage();
    }
}

<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('New Category Created')
            ->success()
            // ->body('Changes to the post have been saved.')
            // ->actions([
            //     Action::make('view')
            //         ->button()
            //         ->markAsRead(),
            // ])
            ->sendToDatabase(auth()->user());
    }
}

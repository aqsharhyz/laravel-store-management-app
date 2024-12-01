<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    // protected function getCreatedNotification(): ?Notification
    // {
    //     return Notification::make()
    //         ->title('Saved successfully yes')
    //         ->broadcast(auth()->user());
    // }
}

<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data);
        // foreach ($data['order_items'] as $key => $orderItem) {
        //     echo $orderItem['product_id'];
        // }

        return $data;
    }
}

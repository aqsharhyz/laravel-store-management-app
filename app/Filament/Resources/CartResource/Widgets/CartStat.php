<?php

namespace App\Filament\Resources\CartResource\Widgets;

use App\Models\Cart;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CartStat extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Carts', Cart::query()->count())
                ->description('All carts from the database')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Average Product per User', Cart::query()->count() / Cart::query()->distinct('user_id')->count())
                ->description('All carts from the database')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Average Quantity per Product', Cart::query()->count() / Cart::query()->distinct('product_id')->count())
                ->description('All carts from the database')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
        ];
    }
}

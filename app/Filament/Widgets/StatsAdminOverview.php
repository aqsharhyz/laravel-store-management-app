<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsAdminOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users', User::query()->count())
                ->description('All registered users from the database')
                ->descriptionIcon('heroicon-m-user')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Products', Product::query()->count())
                ->description('All products from the database')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->chart([3, 2, 5, 3, 7, 4, 9])
                ->color('success'),

            Stat::make('Revenue', Order::query()->where('status', 'completed')->where('order_date', '>=', now()->subDays(30))->sum('total_price'))
                ->description('Total revenue from all completed orders in the last 30 days')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([100, 200, 300, 400, 500, 600, 700])
                ->color('success'),

            Stat::make('Order', Order::query()->where('status', 'completed')->count())
                ->description('All completed orders from the database')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Order', Order::query()->where('order_date', '>=', now()->subDays(7))->count())
                ->description('All orders from the last 7 days')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Order', Order::query()->where('order_date', '>=', now()->subDays(30))->count())
                ->description('All orders from the last 30 days')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}

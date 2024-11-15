<?php

namespace App\Filament\Resources\WishlistResource\Widgets;

use App\Models\Wishlist;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class WishlistTable extends BaseWidget
{
    public function getTableRecordKey($record): string
    {
        return (string) $record->name;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Wishlist::select('products.name', DB::raw('count(wishlists.user_id) as user_count'))
                    ->join('products', 'wishlists.product_id', '=', 'products.id')
                    ->groupBy('products.name')
                    ->orderBy('products.name', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Product Name'),
                Tables\Columns\TextColumn::make('user_count')
                    ->label('User Count')
                    ->numeric(),
            ]);
    }
}

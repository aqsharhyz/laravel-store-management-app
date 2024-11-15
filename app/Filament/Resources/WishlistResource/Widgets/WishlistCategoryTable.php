<?php

namespace App\Filament\Resources\WishlistResource\Widgets;

use App\Models\Wishlist;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class WishlistCategoryTable extends BaseWidget
{
    public function getTableRecordKey($record): string
    {
        return (string) $record->name;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Wishlist::select('categories.name', DB::raw('count(wishlists.user_id) as user_count'))
                    ->join('products', 'wishlists.product_id', '=', 'products.id')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->groupBy('categories.name')
                    ->orderBy('categories.name', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Category'),
                Tables\Columns\TextColumn::make('user_count')
                    ->label('User Count')
                    ->numeric(),
            ]);
    }
}

<?php

namespace App\Filament\Resources\CartResource\Widgets;

use App\Models\Cart;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class CartProductTable extends BaseWidget
{
    public function getTableRecordKey($record): string
    {
        return (string) $record->name;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Cart::select('products.name', DB::raw('count(carts.user_id) as user_count'), DB::raw('count(carts.quantity) as quantity_count'))
                    ->join('products', 'carts.product_id', '=', 'products.id')
                    ->groupBy('products.name')
                    ->orderBy('products.name', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Product Name'),
                Tables\Columns\TextColumn::make('user_count')
                    ->label('User Count')
                    ->numeric(),
                Tables\Columns\TextColumn::make('quantity_count')
                    ->label('Quantity Count')
                    ->numeric(),
            ]);
    }
}

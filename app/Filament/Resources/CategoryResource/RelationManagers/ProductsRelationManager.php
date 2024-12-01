<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->rows(3),
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->type('number')
                    ->label('Price')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                Forms\Components\TextInput::make('stock')
                    ->type('number')
                    ->label('Stock')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                // Forms\Components\Select::make('category_id')
                //     ->label('Category')
                //     ->relationship('category', 'name')
                //     ->searchable()
                //     ->preload()
                //     ->default(function ($request) {
                //         return $request->record->getKey();
                //     })
                //     ->required()
                //     ->createOptionForm([
                //         Forms\Components\TextInput::make('name')
                //             ->required()
                //             ->unique()
                //             ->maxLength(255),
                //         Forms\Components\Textarea::make('description')
                //             ->nullable(),
                //     ]),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'discontinue' => 'Discontinue',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('sku')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->sortable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

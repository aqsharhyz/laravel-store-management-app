<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ProductExporter;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Product';

    protected static ?string $modelLabel = 'Product';

    protected static ?string $navigationGroup = 'Product Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
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
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->unique()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->nullable(),
                    ]),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'discontinue' => 'Discontinue',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ExportAction::make()
                    ->exporter(ProductExporter::class)
                    ->modifyQueryUsing(fn(Builder $query, array $options) => isset($options['status']) ? $query->where('status', $options['status']) : $query)
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->sortable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('stock')
                    ->sortable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'discontinue' => 'Discontinue',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Prroduct Info')
                    ->schema([
                        TextEntry::make('name')->label('Name'),
                        TextEntry::make('sku')->label('SKU'),
                        TextEntry::make('price')->label('Price'),
                        TextEntry::make('stock')->label('Stock'),
                        TextEntry::make('status')->label('Status'),
                        TextEntry::make('category.name')->label('Category'),
                        TextEntry::make('description')->label('Description'),
                        TextEntry::make('created_at')->label('Created At'),
                        TextEntry::make('updated_at')->label('Updated At'),
                    ])->columns(2)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

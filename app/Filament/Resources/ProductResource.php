<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ProductExporter;
use App\Filament\Imports\ProductImporter;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\GlobalSearch\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Product';

    protected static ?string $modelLabel = 'Product';

    protected static ?string $navigationGroup = 'Product Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    protected static int $globalSearchResultsLimit = 20;

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->name;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'sku'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'SKU' => $record->sku,
            'Category' => $record->category->name,
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['category']);
    }

    // public static function getGlobalSearchResultActions(Model $record): array
    // {
    //     return [
    //         Action::make('edit')
    //             ->url(static::getUrl('edit', ['record' => $record])),
    //     ];
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'discontinue' => 'Discontinue',
                    ])
                    ->required(),
                Forms\Components\RichEditor::make('description')
                    ->label('Description')
                    ->required()
                    ->disableToolbarButtons([
                        'codeBlock',
                    ])
                    ->fileAttachmentsDirectory('images/products-description')
                    ->fileAttachmentsVisibility('public')
                    ->disableGrammarly()
                    ->columnSpan('full'),

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
                Forms\Components\Select::make('tags')
                    ->label('Tags')
                    ->relationship('tags', 'name')
                    ->searchable()
                    ->multiple()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->unique()
                            ->maxLength(255),
                    ]),
                // ->createOptionUsing(function (array $data): int {
                //     return \App\Models\Tag::create($data)->getKey();
                // }),
                Forms\Components\FileUpload::make('images')
                    ->label('Image')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '4:3',
                        '1:1',
                    ])
                    ->panelLayout('grid')
                    ->imageEditorEmptyFillColor('#FFFFFF')
                    ->directory('images/products')
                    ->visibility('public')
                    ->multiple()
                    ->reorderable()
                    ->appendFiles(),
                // ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ImportAction::make()
                    ->importer(ProductImporter::class),
                ExportAction::make()
                    ->exporter(ProductExporter::class)
                    ->modifyQueryUsing(fn(Builder $query, array $options) => isset($options['status']) ? $query->where('status', $options['status']) : $query)
            ])
            ->query(Product::with('tags'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    //! remove image
                    // ->description(fn(Product $record): HtmlString => new HtmlString($record->description))
                    // ->html()
                    // ->limit(50)
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
                // Tables\Columns\TextColumn::make('status'),
                Tables\Columns\IconColumn::make('status')
                    ->icon(fn(string $state): string => match ($state) {
                        'active' => 'heroicon-s-check-circle',
                        'inactive' => 'heroicon-s-x-circle',
                        'discontinue' => 'heroicon-s-minus-circle',
                    }),
                Tables\Columns\TextColumn::make('category.name'),

                // Tables\Columns\TextColumn::make('tags')
                //     ->label('Tags'),
                // ->getStateUsing(function ($record) {
                //     // dd($record);
                //     // Ensure that the 'tags' relationship is loaded and contains the tags for the product
                //     if ($record) {
                //         dd($record);
                //         return $record;
                //     }
                //     return '[]';  // Return an empty string if no tags exist
                // }),
                // ->format(fn($record) => $record->tags->pluck('name')->join(', ')),
                // ->listWithLineBreaks(),
                Tables\Columns\ImageColumn::make('images')
                    // ->url(fn($record) => $record->images->first()?->url)
                    // ->directory('images/products')
                    ->visibility('public')
                    // ->width('40px')
                    // ->height('40px')
                    ->square()
                    ->limit(3)
                    ->limitedRemainingText(),
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
                Tables\Filters\TrashedFilter::make(),
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('Delete product can lead to data inconsistency in other parts of the application. Inactivate or discontinue the product instead if you want to keep the data.')
                        ->modalSubmitActionLabel('Delete it with all consequences'),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            // ->selectable()
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // ExportAction::make()
                    //     ->accessSelectedRecords()
                    //     ->exporter(ProductExporter::class)
                    //     ->modifyQueryUsing(function (Builder $query, Collection $selectedRecords) {
                    //         Log::info($selectedRecords->pluck('id'));
                    //         return $query->whereIn('id', $selectedRecords->pluck('id'));
                    //     }),
                    // Tables\Actions\BulkAction::make('Export')
                    //     ->icon('heroicon-o-printer')
                    //     // ->accessSelectedRecords()
                    //     // ->deselectRecordsAfterCompletion()
                    //     ->action(function (Collection $records) {
                    //         ExportAction::make()
                    //             ->exporter(ProductExporter::class)
                    //             ->modifyQueryUsing(function (Builder $query) use ($records) {
                    //                 return $query->whereKey($records->pluck('id'));
                    //             });
                    //     }),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Product Info')
                    ->schema([
                        TextEntry::make('name')->label('Name'),
                        TextEntry::make('sku')->label('SKU'),
                        TextEntry::make('price')->label('Price'),
                        TextEntry::make('stock')->label('Stock'),
                        TextEntry::make('status')->label('Status'),
                        TextEntry::make('category.name')->label('Category'),
                        TextEntry::make('description')
                            ->label('Description')
                            ->columnSpan('full')
                            ->html(),
                        TextEntry::make('created_at')->label('Created At'),
                        TextEntry::make('updated_at')->label('Updated At'),
                        ImageEntry::make('images')
                            // ->checkFileExistence(false)
                            // ->defaultImageUrl(url('/images/placeholder.png')),
                            ->label('Images')
                            // ->visibility('public'),
                            ->columnSpan('full'),
                    ])->columns(2)
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
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

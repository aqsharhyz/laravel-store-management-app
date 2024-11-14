<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Order';

    protected static ?string $modelLabel = 'Order';

    protected static ?string $navigationGroup = 'Order Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        function calculateTotalPrice(Set $set, Get $get)
        {
            $orderDetails = $get('../../orderDetails');

            $totalPrice = 0;
            foreach ($orderDetails as $item) {
                $quantity = (float) ($item['quantity'] ?? 0);
                $priceAtPurchase = (float) ($item['price_at_purchase'] ?? 0);

                $totalPrice += $quantity * $priceAtPurchase;
            }

            $set('../../total_price', $totalPrice);
            // dd($get('../../'));
        }

        return $form
            ->schema([
                Forms\Components\Repeater::make('orderDetails')
                    ->label('Order Details')
                    // ->recordComponent(Forms\Components\OrderDetailComponent::class)
                    ->addActionLabel('Add Order Detail')
                    // ->maxItems(10)
                    ->minItems(1)
                    ->relationship('orderDetails')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $set('price_at_purchase', (float) Product::query()->find($get('product_id'))?->price ?? 0);
                                calculateTotalPrice($set, $get);
                            }),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->live()
                            // ->afterStateUpdated(fn(Set $set) => $set('../total_price', fn(Get $get) => (int) $get('quantity') * (float) $get('price_at_purchase'))),
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                calculateTotalPrice($set, $get);
                            }),

                        Forms\Components\TextInput::make('price_at_purchase')
                            ->label('Price at Purchase')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->live()
                            ->default(0)
                            // ->afterStateUpdated(fn(Set $set) => $set('../total_price', fn(Get $get) => (int) $get('quantity') * (float) $get('price_at_purchase'))),
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                calculateTotalPrice($set, $get);
                            }),
                    ]),

                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                // ->createOptionForm([

                Forms\Components\DateTimePicker::make('order_date')
                    ->label('Order Date')
                    ->required()
                    ->native(false)
                    ->default(now())
                    ->minDate(now()->subYears(1)),


                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'declined' => 'Declined',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('total_price')
                    ->label('Total Price')
                    ->required()
                    ->numeric()
                    // ->live()
                    ->default(0),
                // ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_date')
                    ->label('Order Date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        // return match ($state) {
                        //     'pending' => 'Pending',
                        //     'processing' => 'Processing',
                        //     'completed' => 'Completed',
                        //     'declined' => 'Declined',
                        //     default => $state,
                        // };
                        return ucwords($state);
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_details_count')
                    ->label('Total Products')
                    ->counts('orderDetails')
                    ->numeric()
                    ->sortable(),
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
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'declined' => 'Declined',
                    ]),
                Filter::make('order_date')
                    ->form([
                        DatePicker::make('order_date_from'),
                        DatePicker::make('order_date_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['order_date_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('order_date', '>=', $date),
                            )
                            ->when(
                                $data['order_date_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('order_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
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
                Section::make('Product Info')
                    ->schema([
                        TextEntry::make('user.name')->label('User'),
                        TextEntry::make('order_date')->label('Order Date'),
                        TextEntry::make('status')->label('Status'),
                        TextEntry::make('total_price')->label('Total Price'),
                        TextEntry::make('order_details_count')->label('Total Products'),
                        TextEntry::make('created_at')->label('Created At'),
                        TextEntry::make('updated_at')->label('Updated At'),
                    ])->columns(2),

                Section::make('Order Details')
                    ->schema([
                        RepeatableEntry::make('orderDetails')
                            ->label('')
                            // ->recordComponent(Components\OrderDetailComponent::class)
                            ->columns(2)

                            ->schema([
                                TextEntry::make('product.name')->label('Product'),
                                TextEntry::make('quantity')->label('Quantity'),
                                TextEntry::make('price_at_purchase')->label('Price at Purchase'),
                            ]),

                    ])->columns(2),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Exports;

use App\Models\Product;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public function getFormats(): array
    {
        return [
            ExportFormat::Csv,
            ExportFormat::Xlsx,
        ];
    }

    public function getFileName(Export $export): string
    {
        return "products-{$export->getKey()}.csv";
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name'),
            ExportColumn::make('description'),
            // ->enabledByDefault(false),
            ExportColumn::make('sku')
                ->label('SKU'),
            ExportColumn::make('price'),
            ExportColumn::make('stock'),
            ExportColumn::make('status'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('category.name')
                ->label('Category'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your product export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    //     public static function modifyQuery(Builder $query): Builder
    // {
    //     return $query->with([
    //         'purchasable' => fn (MorphTo $morphTo) => $morphTo->morphWith([
    //             ProductPurchase::class => ['product'],
    //             ServicePurchase::class => ['service'],
    //             Subscription::class => ['plan'],
    //         ]),
    //     ]);
    // }
}

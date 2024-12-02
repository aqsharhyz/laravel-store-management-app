<?php

namespace App\Filament\Imports;

use App\Models\Product;
use Carbon\CarbonInterface;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->examples(['Product 1', 'Product 2'])
                ->rules(['required', 'max:255', 'unique:products,name']),
            ImportColumn::make('description')
                ->requiredMapping()
                ->examples(['Product 1 description', '<b>Product 2 description</b>'])
                ->rules(['required']),
            ImportColumn::make('sku')
                ->label('SKU')
                ->requiredMapping()
                ->examples(['ABC123', 'DEF456'])
                ->exampleHeader('SKU')
                ->rules(['required', 'max:255', 'unique:products,sku']),
            // ->fillRecordUsing(function (Product $record, string $state): void {
            //     $record->sku = strtoupper($state);
            // }),
            ImportColumn::make('price')
                ->requiredMapping()
                ->numeric(decimalPlaces: 2)
                ->examples(['100', '200.6'])
                ->rules(['required', 'numeric', 'min:0']),
            ImportColumn::make('stock')
                ->requiredMapping()
                ->numeric()
                ->examples(['10', '20'])
                ->rules(['required', 'integer', 'min:0']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->examples(['active', 'inactive', 'discontinue'])
                ->rules(['required', 'in:active,inactive,discontinue']),
            ImportColumn::make('category')
                ->requiredMapping()
                ->relationship(resolveUsing: ['name'])
                ->rules(['required', 'exists:categories,name']),
        ];
    }

    public function getJobRetryUntil(): ?CarbonInterface
    {
        return now()->addSeconds(5);
    }

    public function getValidationMessages(): array
    {
        return [
            'category.exists' => 'The selected category does not exist in the database.',
        ];
    }

    public function resolveRecord(): ?Product
    {
        // return Product::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Product();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your product import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}

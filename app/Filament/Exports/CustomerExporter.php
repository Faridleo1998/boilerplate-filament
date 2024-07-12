<?php

namespace App\Filament\Exports;

use App\Models\Customer;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CustomerExporter extends Exporter
{
    protected static ?string $model = Customer::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('identification_type')
                ->label(__('resources.customer.labels.identification_type'))
                ->state(fn($record) => $record->identification_type->getLabel()),
            ExportColumn::make('identification_number')
                ->label(__('labels.identification_number')),
            ExportColumn::make('names')
                ->label(__('resources.customer.labels.names')),
            ExportColumn::make('last_names')
                ->label(__('resources.customer.labels.last_names')),
            ExportColumn::make('born_date')
                ->label(__('labels.born_date')),
            ExportColumn::make('is_featured')
                ->label(__('resources.customer.labels.is_featured'))
                ->state(fn($record) => $record->is_featured ? __('resources.customer.values.is_featured_true') : __('resources.customer.values.is_featured_false')),
            ExportColumn::make('email')
                ->label(__('labels.email')),
            ExportColumn::make('phone')
                ->label(__('labels.phone')),
            ExportColumn::make('address')
                ->label(__('labels.address')),
            ExportColumn::make('created_at')
                ->label(__('labels.created_at')),
            ExportColumn::make('createdBy.full_name')
                ->label(__('labels.created_by')),
            ExportColumn::make('country.name')
                ->label(__('labels.country')),
            ExportColumn::make('state.name')
                ->label(__('labels.state')),
            ExportColumn::make('city.name')
                ->label(__('labels.city')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = number_format($export->successful_rows);

        $body = __('notifications.export_completed', [
            'resource' => __('resources.customer.plural_label'),
            'rowsCount' => number_format($export->successful_rows),
        ]);

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public function getFileName(Export $export): string
    {
        return __('resources.customer.plural_label') . '-' . now()->format('Y-m-d');
    }
}

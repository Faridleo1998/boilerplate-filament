<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Exports\CustomerExporter;
use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label(__('actions.export'))
                ->exporter(CustomerExporter::class)
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray'),
            Actions\CreateAction::make(),
        ];
    }
}

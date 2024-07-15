<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Exports\CustomerExporter;
use App\Filament\Resources\CustomerResource;
use App\Models\Customer;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Gate;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label(__('actions.export'))
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(CustomerExporter::class)
                ->visible(Gate::allows('export', Customer::class)),
            Actions\CreateAction::make(),
        ];
    }
}

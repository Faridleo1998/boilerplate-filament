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
                ->modalHeading(__('actions.export') . ' ' . __('resources.customer.plural_label'))
                ->color('success')
                ->exporter(CustomerExporter::class)
                ->visible(Gate::allows('export', Customer::class)),
            Actions\CreateAction::make(),
        ];
    }
}

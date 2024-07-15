<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Exports\CustomerExporter;
use App\Filament\Resources\CustomerResource;
use App\Models\Customer;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->fastPaginate(($this->getTableRecordsPerPage() === 'all') ? $query->count() : $this->getTableRecordsPerPage());
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label(__('actions.export'))
                ->modalHeading(__('actions.export') . ' ' . __('resources.customer.plural_label'))
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(CustomerExporter::class)
                ->visible(Gate::allows('export', Customer::class)),
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Traits\SanitizeFields;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    use SanitizeFields;

    protected static string $resource = CustomerResource::class;

    protected static bool $canCreateAnother = false;

    private array $includeFields = [
        'identification_number',
        'names',
        'last_names',
        'email',
        'phone',
        'address',
    ];

    public function sanitizeData(array $data): array
    {
        $data = $this->sanitize($data, $this->includeFields);

        return $data;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->sanitize($data, $this->includeFields);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Traits\SanitizeFields;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    use SanitizeFields;

    protected static string $resource = UserResource::class;

    private array $excludeFields = [
        'password',
        'status',
        'birth_date',
        'roles',
    ];

    public function sanitizeData(array $data): array
    {
        $data = $this->sanitize($data, $this->excludeFields, 'exclude');

        if (array_key_exists('full_name', $data)) {
            $data['full_name'] = ucwords(strtolower($data['full_name']));
        }

        if (array_key_exists('email', $data)) {
            $data['email'] = strtolower($data['email']);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false)
                ->mutateFormDataUsing(function (array $data) {
                    $data = $this->sanitizeData($data);

                    return $data;
                }),
        ];
    }
}

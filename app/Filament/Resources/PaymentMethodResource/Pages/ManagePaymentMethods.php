<?php

namespace App\Filament\Resources\PaymentMethodResource\Pages;

use App\Filament\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use App\Traits\SanitizeFields;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;

class ManagePaymentMethods extends ManageRecords
{
    use SanitizeFields;

    protected static string $resource = PaymentMethodResource::class;

    private array $includeFields = [
        'name',
    ];

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false)
                ->modalWidth(MaxWidth::Large)
                ->mutateFormDataUsing(fn(array $data) => $this->sanitize($data, $this->includeFields))
                ->action(function (array $data, Action $action) {
                    PaymentMethod::restoreAndUpdateWithTrashed(
                        ['name' => $data['name']],
                        $data,
                    );

                    $action->success();
                }),
        ];
    }
}

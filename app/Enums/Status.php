<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Status: int implements HasColor, HasLabel
{
    case INACTIVE = 0;
    case ACTIVE = 1;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::INACTIVE => __('enums/status.inactive'),
            self::ACTIVE => __('enums/status.active'),
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::INACTIVE => 'danger',
            self::ACTIVE => 'success',
        };
    }
}

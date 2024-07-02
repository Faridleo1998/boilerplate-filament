<?php

namespace App\Enums\Enums;

use Filament\Support\Contracts\HasLabel;

enum IdentificationTypeEnum: string implements HasLabel
{
    case CC = 'CC';
    case NIT = 'NIT';
    case PAS = 'PAS';
    case CE = 'CE';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CC => __('enums/identification-type.cc'),
            self::NIT => __('enums/identification-type.nit'),
            self::PAS => __('enums/identification-type.pas'),
            self::CE => __('enums/identification-type.ce'),
        };
    }
}

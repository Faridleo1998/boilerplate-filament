<?php

namespace App\Traits\Models\Attributes;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait PaymentMethodAttributes
{
    protected function name(): Attribute
    {
        return new Attribute(
            set: fn($value): string => ucwords(strtolower($value)),
        );
    }
}

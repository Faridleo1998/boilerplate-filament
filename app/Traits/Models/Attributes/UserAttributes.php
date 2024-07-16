<?php

namespace App\Traits\Models\Attributes;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait UserAttributes
{
    protected function email(): Attribute
    {
        return new Attribute(
            set: fn($value): string => strtolower($value),
        );
    }

    protected function fullName(): Attribute
    {
        return new Attribute(
            set: fn($value): string => ucwords(strtolower($value)),
        );
    }
}

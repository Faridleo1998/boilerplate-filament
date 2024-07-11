<?php

namespace App\Traits\Models\Attributes;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait CustomerAttributes
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
            get: fn(): string => "{$this->names} {$this->last_names}",
        );
    }

    protected function lastNames(): Attribute
    {
        return new Attribute(
            set: fn($value): string => ucwords(strtolower($value)),
        );
    }

    protected function names(): Attribute
    {
        return new Attribute(
            set: fn($value): string => ucwords(strtolower($value)),
        );
    }
}

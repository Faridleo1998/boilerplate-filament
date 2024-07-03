<?php

namespace App\Models;

use App\Enums\Enums\IdentificationTypeEnum;
use App\Traits\Models\HasCreatedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Nnjeim\World\Models\City;
use Nnjeim\World\Models\Country;
use Nnjeim\World\Models\State;

class Customer extends Model
{
    use HasCreatedBy, HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'born_date' => 'date',
            'identification_type' => IdentificationTypeEnum::class,
        ];
    }

    // Mutators and Accessors
    protected function names(): Attribute
    {
        return new Attribute(
            set: fn ($value) => ucwords(strtolower($value)),
        );
    }

    protected function lastNames(): Attribute
    {
        return new Attribute(
            set: fn ($value) => ucwords(strtolower($value)),
        );
    }

    protected function fullName(): Attribute
    {
        return new Attribute(
            get: fn () => "{$this->names} {$this->last_names}",
        );
    }

    protected function email(): Attribute
    {
        return new Attribute(
            set: fn ($value) => strtolower($value),
        );
    }
}

<?php

namespace App\Models;

use App\Enums\IdentificationType;
use App\Traits\Models\Attributes\CustomerAttributes;
use App\Traits\Models\HasCreatedBy;
use App\Traits\Models\Relations\CustomerRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use CustomerAttributes, CustomerRelations;
    use HasCreatedBy, HasFactory;
    use SoftDeletes;

    const UPDATED_AT = null;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'born_date' => 'date',
            'identification_type' => IdentificationType::class,
        ];
    }
}

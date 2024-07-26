<?php

namespace App\Models;

use App\Enums\IdentificationTypeEnum;
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
            'identification_type' => IdentificationTypeEnum::class,
        ];
    }
}

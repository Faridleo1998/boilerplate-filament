<?php

namespace App\Models;

use App\Enums\Status;
use App\Traits\Models\Attributes\PaymentMethodAttributes;
use App\Traits\Models\HasCreatedBy;
use App\Traits\Models\Relations\PaymentMethodRelations;
use App\Traits\Models\RestoreAndUpdateWithTrashed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PaymentMethod extends Model implements HasMedia
{
    use HasCreatedBy, HasFactory, InteractsWithMedia, SoftDeletes;
    use PaymentMethodAttributes, PaymentMethodRelations;
    use RestoreAndUpdateWithTrashed;

    public $timestamps = false;

    protected $guarded = [
        'id',
        'created_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'is_digital' => 'boolean',
        ];
    }
}

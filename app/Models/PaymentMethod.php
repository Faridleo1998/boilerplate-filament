<?php

namespace App\Models;

use App\Enums\Status;
use App\Traits\Models\Attributes\PaymentMethodAttributes;
use App\Traits\Models\HasCreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PaymentMethod extends Model implements HasMedia
{
    use HasCreatedBy, HasFactory, InteractsWithMedia;
    use PaymentMethodAttributes;

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

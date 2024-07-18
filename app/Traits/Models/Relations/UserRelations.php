<?php

namespace App\Traits\Models\Relations;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserRelations
{
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'created_by');
    }
}

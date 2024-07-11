<?php

namespace App\Traits\Models\Relations;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserRelations
{
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'created_by');
    }
}

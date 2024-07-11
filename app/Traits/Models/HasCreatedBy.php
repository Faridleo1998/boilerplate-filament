<?php

namespace App\Traits\Models;

use App\Models\User;

trait HasCreatedBy
{
    public static function bootHasCreatedBy()
    {
        if (auth()->check()) {
            static::creating(function ($model) {
                $model->created_by = auth()->id();
            });
        }
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

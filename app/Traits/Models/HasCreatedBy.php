<?php

namespace App\Traits\Models;

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
}

<?php

namespace App\Traits\Models;

trait HasCreatedBy
{
    public static function bootHasCreatedBy()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });
    }
}

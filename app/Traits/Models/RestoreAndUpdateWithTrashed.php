<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait RestoreAndUpdateWithTrashed
{
    public static function restoreAndUpdateWithTrashed(array $attributes, array $data): Model
    {
        $record = self::withTrashed()->firstOrNew($attributes);

        if ($record->trashed()) {
            $record->restore();
        }

        $record->fill($data)->save();

        return $record;
    }
}

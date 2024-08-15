<?php

namespace App\Traits;

trait SanitizeFields
{
    public function sanitize(array $data, array $fields = [], string $mode = 'include')
    {
        $sanitizedData = [];

        foreach ($data as $field => $value) {
            $shouldSanitize = match ($mode) {
                'include' => in_array($field, $fields),
                'exclude' => ! in_array($field, $fields),
                default => false,
            };

            $sanitizedData[$field] = $shouldSanitize ? $this->sanitizeValue($value) : $value;
        }

        return $sanitizedData;
    }

    public function sanitizeValue($value)
    {
        if (is_string($value)) {
            $value = trim($value);
        }

        return $value;
    }
}

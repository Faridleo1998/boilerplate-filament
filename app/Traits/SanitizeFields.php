<?php

namespace App\Traits;

trait SanitizeFields
{
    public function sanitize(array $data, array $fields = [], string $mode = 'include')
    {
        $sanitizedData = [];

        if ($mode === 'include') {
            foreach ($fields as $field) {
                $sanitizedData[$field] = $this->sanitizeValue($data[$field]) ?? null;
            }
        } elseif ($mode === 'exclude') {
            foreach ($data as $key => $value) {
                if (! in_array($key, $fields)) {
                    $sanitizedData[$key] = $this->sanitizeValue($value);
                } else {
                    $sanitizedData[$key] = $value;
                }
            }
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

<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

if (! function_exists('get_company_info')) {
    function get_company_info(array $fields = [], string $mode = 'include'): array
    {
        $settings = Cache::get('settings');

        if (! $settings) {
            $settings = Setting::first()->toArray();
            Cache::put('settings', $settings);
        }

        $method = $mode === 'include' ? 'only' : 'except';

        return Arr::$method($settings, $fields);
    }
}

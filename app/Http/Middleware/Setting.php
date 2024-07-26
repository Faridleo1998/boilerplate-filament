<?php

namespace App\Http\Middleware;

use App\Models\Setting as SettingModel;
use Closure;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class Setting
{
    public function handle(Request $request, Closure $next): Response
    {
        $settings = Cache::get('settings');

        if (! $settings) {
            $settings = SettingModel::first(['name', 'theme_color'])->toArray();
            Cache::put('settings', $settings);
        }

        if ($settings['theme_color']) {
            FilamentColor::register([
                'primary' => $settings['theme_color'],
            ]);
        }

        if ($settings['name']) {
            config([
                'app.name' => $settings['name'],
            ]);
        }

        return $next($request);
    }
}

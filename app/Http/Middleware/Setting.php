<?php

namespace App\Http\Middleware;

use App\Models\Setting as SettingModel;
use Closure;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Setting
{
    public function handle(Request $request, Closure $next): Response
    {
        $settings = SettingModel::first();

        if ($settings?->theme_color) {
            FilamentColor::register([
                'primary' => $settings->theme_color,
            ]);
        }

        if ($settings?->name) {
            config([
                'app.name' => $settings->name,
            ]);
        }

        return $next($request);
    }
}

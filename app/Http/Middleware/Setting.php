<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function App\Helpers\get_company_info;

class Setting
{
    public function handle(Request $request, Closure $next): Response
    {
        $settings = get_company_info(['theme_color', 'name']);

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

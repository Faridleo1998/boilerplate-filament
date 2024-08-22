<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class FilamentHooksProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn() => Str::limit(auth()->user()->full_name, 15),
        );
    }
}

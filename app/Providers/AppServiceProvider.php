<?php

namespace App\Providers;

use Filament\Forms\Components\Toggle;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Table::$defaultDateTimeDisplayFormat = 'M j, Y g:i a';

        Toggle::configureUsing(function (Toggle $toggle): void {
            $toggle->inline(false);
        });

        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn() => auth()->user()->full_name,
        );

        if ($this->app->environment('local')) {
            $this->app->register(TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}

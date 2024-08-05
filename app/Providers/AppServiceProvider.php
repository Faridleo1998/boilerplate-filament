<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Forms\Components\Toggle;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
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

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['es', 'en'])
                ->flags([
                    'es' => asset('flags/es.svg'),
                    'en' => asset('flags/sh.svg'),
                ]);
        });
    }
}

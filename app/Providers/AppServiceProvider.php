<?php

namespace App\Providers;

use Filament\Forms\Components\Toggle;
use Filament\Tables\Table;
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
    }
}

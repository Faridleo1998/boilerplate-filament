<?php

namespace App\Providers;

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
    }
}

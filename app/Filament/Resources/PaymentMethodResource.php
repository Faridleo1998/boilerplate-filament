<?php

namespace App\Filament\Resources;

use App\Enums\Status;
use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Models\PaymentMethod;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class PaymentMethodResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = PaymentMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    protected static bool $hasTitleCaseModelLabel = false;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'create',
            'update',
            'delete',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('labels.name'))
                    ->required()
                    ->maxLength(255),
                SpatieMediaLibraryFileUpload::make('logo')
                    ->collection('logo')
                    ->acceptedFileTypes(['image/png', 'image/jpg', 'image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(1024)
                    ->optimize('webp')
                    ->imageEditor()
                    ->helperText(new HtmlString(__('resources.setting.helper_text.image_field')))
                    ->columnSpanFull(),
                SpatieMediaLibraryFileUpload::make('qr')
                    ->label(__('labels.qr_code'))
                    ->collection('qr')
                    ->acceptedFileTypes(['image/png', 'image/jpg', 'image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(1024)
                    ->optimize('webp')
                    ->imageEditor()
                    ->helperText(new HtmlString(__('resources.setting.helper_text.image_field')))
                    ->columnSpanFull(),
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Toggle::make('is_digital')
                            ->label(__('resources.payment_method.labels.is_digital'))
                            ->default(false),
                        Forms\Components\Toggle::make('status')
                            ->label(__('labels.status'))
                            ->default(true),
                    ])
                    ->columns(2),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('labels.name'))
                    ->searchable(),
                SpatieMediaLibraryImageColumn::make('logo')
                    ->collection('logo'),
                SpatieMediaLibraryImageColumn::make('qr')
                    ->label(__('labels.qr_code'))
                    ->collection('qr')
                    ->action(
                        fn($record) => response()->download($record->getFirstMediaPath('logo'))
                    ),
                Tables\Columns\IconColumn::make('is_digital')
                    ->label(__('resources.payment_method.labels.is_digital')),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('labels.status'))
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('labels.created_at'))
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('labels.status'))
                    ->options(Status::class),
                Tables\Filters\TernaryFilter::make('is_digital')
                    ->label(__('resources.payment_method.labels.is_digital')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth(MaxWidth::Large),
                Tables\Actions\DeleteAction::make(),
            ])
            ->reorderable('order_column')
            ->defaultSort('order_column');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePaymentMethods::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->count();
    }

    public static function getLabel(): string
    {
        return __('resources.payment_method.singular_label');
    }

    public static function getPluralLabel(): string
    {
        return __('resources.payment_method.plural_label');
    }
}

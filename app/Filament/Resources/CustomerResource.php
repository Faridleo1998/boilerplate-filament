<?php

namespace App\Filament\Resources;

use App\Enums\Enums\IdentificationTypeEnum;
use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

class CustomerResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
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
                Forms\Components\Section::make(__('resources.customer.sections.personal_information'))
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\Select::make('identification_type')
                            ->label(__('resources.customer.labels.identification_type'))
                            ->required()
                            ->options(IdentificationTypeEnum::class)
                            ->default(IdentificationTypeEnum::CC)
                            ->selectablePlaceholder(false),
                        Forms\Components\TextInput::make('identification_number')
                            ->label(__('labels.identification_number'))
                            ->required()
                            ->numeric()
                            ->autocomplete('off')
                            ->regex('/^[0-9-]+$/i'),
                        Forms\Components\TextInput::make('names')
                            ->label(__('resources.customer.labels.names'))
                            ->required()
                            ->autocomplete('off'),
                        Forms\Components\TextInput::make('last_names')
                            ->label(__('resources.customer.labels.last_names'))
                            ->required()
                            ->autocomplete('off'),
                        Forms\Components\DatePicker::make('born_date')
                            ->label(__('labels.born_date')),
                        Forms\Components\Toggle::make('is_featured')
                            ->label(__('resources.customer.labels.is_featured'))
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('tooltips.is_featured'))
                            ->default(false),
                    ])
                    ->columns([
                        'md' => 2,
                        'lg' => 3,
                        '2xl' => 4,
                    ]),
                Forms\Components\Section::make(__('resources.customer.sections.contact_information'))
                    ->icon('icon-address-book')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->label(__('labels.email'))
                            ->required()
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->autocomplete('off'),
                        PhoneInput::make('phone')
                            ->label(__('labels.phone'))
                            ->displayNumberFormat(PhoneInputNumberType::E164),
                        Forms\Components\TextInput::make('address')
                            ->label(__('labels.address'))
                            ->autocomplete('off'),
                    ])
                    ->columns([
                        'lg' => 3,
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('identification_type')
                    ->label(__('resources.customer.labels.identification_type'))
                    ->state(fn ($record) => $record->identification_type->value)
                    ->tooltip(fn (Customer $record): string => $record->identification_type->getLabel()),
                Tables\Columns\TextColumn::make('identification_number')
                    ->label(__('labels.identification_number'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label(__('resources.customer.labels.full_name'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('labels.email'))
                    ->searchable()
                    ->sortable(),
                PhoneColumn::make('phone')
                    ->label(__('labels.phone'))
                    ->displayFormat(PhoneInputNumberType::E164)
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('address')
                    ->label(__('labels.address'))
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label(__('resources.customer.labels.is_featured'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('labels.created_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdBy.full_name')
                    ->label(__('labels.created_by'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label(__('resources.customer.labels.is_featured')),
                Tables\Filters\SelectFilter::make('identification_type')
                    ->label(__('resources.customer.labels.identification_type'))
                    ->options(IdentificationTypeEnum::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return __('resources.customer.singular_label');
    }
}

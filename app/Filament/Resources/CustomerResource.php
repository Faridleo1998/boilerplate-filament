<?php

namespace App\Filament\Resources;

use App\Enums\IdentificationTypeEnum;
use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use App\Models\Setting;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Nnjeim\World\Models\City;
use Nnjeim\World\Models\Country;
use Nnjeim\World\Models\State;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

class CustomerResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'createdBy:id,full_name',
                'country:id,name',
                'state:id,name',
                'city:id,name',
            ]);
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'export',
        ];
    }

    public static function form(Form $form): Form
    {
        $setting = Setting::first(['default_country_id', 'default_state_id', 'default_city_id']);

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
                            ->minLength(6)
                            ->autofocus()
                            ->autocomplete('off'),
                        Forms\Components\TextInput::make('names')
                            ->label(__('resources.customer.labels.names'))
                            ->required()
                            ->minLength(3)
                            ->maxLength(30)
                            ->autocomplete('off'),
                        Forms\Components\TextInput::make('last_names')
                            ->label(__('resources.customer.labels.last_names'))
                            ->required()
                            ->minLength(3)
                            ->maxLength(30)
                            ->autocomplete('off'),
                        Forms\Components\DatePicker::make('born_date')
                            ->label(__('labels.born_date'))
                            ->maxDate(now()),
                        Forms\Components\Toggle::make('is_featured')
                            ->label(__('resources.customer.labels.is_featured'))
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('tooltips.is_featured'))
                            ->default(false),
                    ])
                    ->columns([
                        'sm' => 2,
                        'md' => 3,
                        '2xl' => 4,
                    ]),
                Forms\Components\Section::make(__('resources.customer.sections.contact_information'))
                    ->icon('icon-address-book')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->label(__('labels.email'))
                            ->required()
                            ->email()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->autocomplete('off'),
                        PhoneInput::make('phone')
                            ->label(__('labels.phone'))
                            ->displayNumberFormat(PhoneInputNumberType::E164),
                        Forms\Components\TextInput::make('address')
                            ->label(__('labels.address'))
                            ->autocomplete('off'),
                        Forms\Components\Select::make('country_id')
                            ->label(__('labels.country'))
                            ->options(
                                Country::select(['id', 'name'])
                                    ->pluck('name', 'id')
                                    ->toArray()
                            )
                            ->optionsLimit(10)
                            ->searchable(['name'])
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set): void {
                                $set('state_id', null);
                                $set('city_id', null);
                            })
                            ->afterStateHydrated(function (Set $set, string $context) use ($setting): void {
                                if ($context !== 'create') {
                                    return;
                                }

                                $defaultCountry = $setting?->default_country_id;
                                $set('country_id', $defaultCountry);
                            }),
                        Forms\Components\Select::make('state_id')
                            ->label(__('labels.state'))
                            ->options(
                                fn(Get $get): Collection => State::select(['id', 'name'])
                                    ->where('country_id', $get('country_id'))
                                    ->pluck('name', 'id')
                            )
                            ->optionsLimit(10)
                            ->searchable(['name'])
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn(Set $set) => $set('city_id', null))
                            ->afterStateHydrated(function (Set $set, string $context) use ($setting): void {
                                if ($context !== 'create') {
                                    return;
                                }

                                $defaultState = $setting?->default_state_id;
                                $set('state_id', $defaultState);
                            }),
                        Forms\Components\Select::make('city_id')
                            ->label(__('labels.city'))
                            ->options(
                                fn(Get $get): Collection => City::select(['id', 'name'])
                                    ->where('state_id', $get('state_id'))
                                    ->pluck('name', 'id')
                            )
                            ->optionsLimit(10)
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateHydrated(function (Set $set, string $context) use ($setting): void {
                                if ($context !== 'create') {
                                    return;
                                }

                                $defaultCity = $setting?->default_city_id;
                                $set('city_id', $defaultCity);
                            }),
                    ])
                    ->columns([
                        'sm' => 2,
                        'md' => 3,
                        '2xl' => 4,
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('identification_type')
                    ->label(__('resources.customer.labels.identification_type'))
                    ->state(fn($record) => $record->identification_type->value)
                    ->tooltip(fn(Customer $record): string => $record->identification_type->getLabel()),
                Tables\Columns\TextColumn::make('identification_number')
                    ->label(__('labels.identification_number'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label(__('resources.customer.labels.full_name'))
                    ->searchable(['names', 'last_names'])
                    ->sortable()
                    ->limit(20)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        return $state;
                    }),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label(__('resources.customer.labels.is_featured'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('labels.email'))
                    ->searchable()
                    ->sortable(),
                PhoneColumn::make('phone')
                    ->label(__('labels.phone'))
                    ->displayFormat(PhoneInputNumberType::E164)
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('country.name')
                    ->label(__('labels.country'))
                    ->sortable()
                    ->limit(10)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        return $state;
                    }),
                Tables\Columns\TextColumn::make('state.name')
                    ->label(__('labels.state'))
                    ->sortable()
                    ->limit(10)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        return $state;
                    }),
                Tables\Columns\TextColumn::make('city.name')
                    ->label(__('labels.city'))
                    ->sortable()
                    ->limit(10)
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        return $state;
                    }),
                Tables\Columns\TextColumn::make('address')
                    ->label(__('labels.address'))
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('labels.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('created_by')
                    ->label(__('labels.created_by'))
                    ->relationship(
                        'createdBy',
                        'full_name',
                        modifyQueryUsing: fn(Builder $query): Builder => $query->whereHas('customers')
                    )
                    ->searchable()
                    ->optionsLimit(10)
                    ->preload(),
                Tables\Filters\Filter::make('location')
                    ->indicateUsing(function (array $data) {
                        $ubication = [];

                        if (isset($data['country_id'])) {
                            $ubication[] = Country::find($data['country_id'], ['name'])->name;
                        }

                        if (isset($data['state_id'])) {
                            $ubication[] = State::find($data['state_id'], ['name'])->name;
                        }

                        if (isset($data['city_id'])) {
                            $ubication[] = City::find($data['city_id'], ['name'])->name;
                        }

                        return implode(' - ', $ubication);
                    })
                    ->form([
                        Forms\Components\Select::make('country_id')
                            ->label(__('labels.country'))
                            ->getSearchResultsUsing(fn(string $search): array => Country::where('name', 'like', "%{$search}%")->limit(10)->pluck('name', 'id')->toArray())
                            ->getOptionLabelUsing(fn($value): ?string => Country::find($value)?->name)
                            ->optionsLimit(10)
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set): void {
                                $set('state_id', null);
                                $set('city_id', null);
                            }),
                        Forms\Components\Select::make('state_id')
                            ->label(__('labels.state'))
                            ->options(
                                function (Get $get): ?Collection {
                                    if (! $get('country_id')) {
                                        return null;
                                    }

                                    return State::select(['id', 'name'])
                                        ->where('country_id', $get('country_id'))
                                        ->pluck('name', 'id');
                                }
                            )
                            ->optionsLimit(10)
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn(Set $set) => $set('city_id', null)),
                        Forms\Components\Select::make('city_id')
                            ->label(__('labels.city'))
                            ->options(
                                function (Get $get) {
                                    if (! $get('state_id')) {
                                        return null;
                                    }

                                    return City::select(['id', 'name'])
                                        ->where('state_id', $get('state_id'))
                                        ->pluck('name', 'id');
                                }
                            )
                            ->optionsLimit(10)
                            ->searchable()
                            ->preload()
                            ->live(),

                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['country_id'],
                                fn(Builder $query, $countryId): Builder => $query->where('country_id', $countryId)
                            )
                            ->when(
                                $data['state_id'],
                                fn(Builder $query, $stateId): Builder => $query->where('state_id', $stateId)
                            )
                            ->when(
                                $data['city_id'],
                                fn(Builder $query, $cityId): Builder => $query->where('city_id', $cityId)
                            );
                    })
                    ->columns([
                        'sm' => 2,
                        'md' => 3,
                    ]),
            ], layout: FiltersLayout::Modal)
            ->filtersFormColumns([
                'sm' => 2,
                'md' => 3,
            ])
            ->filtersFormWidth(MaxWidth::FourExtraLarge)
            ->filtersFormSchema(fn(array $filters): array => [
                $filters['is_featured'],
                $filters['identification_type'],
                $filters['created_by'],
                Forms\Components\Section::make()
                    ->schema([
                        $filters['location'],
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): string
    {
        return __('resources.customer.singular_label');
    }

    public static function getSlug(): string
    {
        return strtolower(static::getPluralModelLabel());
    }
}

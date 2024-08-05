<?php

namespace App\Filament\Resources;

use App\Enums\Status;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\ManageUsers;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

class UserResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'restore',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('resources.user.sections.personal_information'))
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\TextInput::make('identification_number')
                            ->label(__('labels.identification_number'))
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true)
                            ->disabled(
                                fn(?Model $record): bool => $record?->id === 1
                            ),
                        Forms\Components\TextInput::make('full_name')
                            ->label(__('labels.full_name'))
                            ->required()
                            ->maxLength(255)
                            ->disabled(
                                fn(?Model $record): bool => $record?->id === 1
                            ),
                        Forms\Components\DatePicker::make('birth_date')
                            ->label(__('labels.birth_date'))
                            ->maxDate(now())
                            ->disabled(
                                fn(?Model $record): bool => $record?->id === 1
                            ),
                    ])
                    ->columns([
                        'sm' => 2,
                        'md' => 3,
                    ])
                    ->collapsible(),
                Forms\Components\Section::make(__('resources.user.sections.contact_information'))
                    ->icon('icon-address-book')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->label(__('labels.email'))
                            ->required()
                            ->email()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->disabled(
                                fn(?Model $record): bool => $record?->id === 1
                            ),
                        PhoneInput::make('phone')
                            ->label(__('labels.phone'))
                            ->displayNumberFormat(PhoneInputNumberType::E164)
                            ->disabled(
                                fn(?Model $record): bool => $record?->id === 1
                            ),
                        Forms\Components\TextInput::make('address')
                            ->label(__('labels.address'))
                            ->maxLength(255)
                            ->disabled(
                                fn(?Model $record): bool => $record?->id === 1
                            ),
                    ])
                    ->columns([
                        'sm' => 2,
                        'md' => 3,
                    ])
                    ->collapsible(),
                Forms\Components\Section::make(__('resources.user.sections.account_information'))
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->label(__('labels.status'))
                            ->default(true)
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('tooltips.user.status'))
                            ->disabled(
                                fn(?Model $record): bool => $record?->id === 1 || auth()->user()->id === $record?->id
                            )
                            ->dehydrated(),
                        Forms\Components\TextInput::make('password')
                            ->label(__('labels.password'))
                            ->required(
                                fn(string $context): bool => $context === 'create'
                            )
                            ->minLength(8)
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(
                                fn(?string $state): ?string => Hash::make($state)
                            )
                            ->dehydrated(
                                fn(?string $state): bool => filled($state)
                            ),
                        Forms\Components\Select::make('roles')
                            ->relationship(
                                'roles',
                                'name',
                                modifyQueryUsing: function (Builder $query, ?Model $record): Builder {
                                    return $record?->id === 1
                                        ? $query
                                        : $query->whereNot('name', 'super_admin');
                                }
                            )
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->disabled(
                                fn(?Model $record): bool => $record?->id === 1 || auth()->user()->id === $record?->id
                            )
                            ->dehydrated(),
                    ])
                    ->collapsible()
                    ->columns([
                        'sm' => 2,
                        'md' => 3,
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('identification_number')
                    ->label(__('labels.identification_number'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label(__('labels.full_name'))
                    ->searchable()
                    ->sortable(),
                PhoneColumn::make('phone')
                    ->displayFormat(PhoneInputNumberType::E164)
                    ->label(__('labels.phone'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('labels.status'))
                    ->badge(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('labels.email'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles_count')
                    ->label(__('resources.user.labels.roles_count'))
                    ->counts('roles')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('labels.created_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdBy.full_name')
                    ->label(__('labels.created_by'))
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('labels.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('labels.status'))
                    ->options(Status::class),
                Tables\Filters\TrashedFilter::make()
                    ->visible(fn() => Gate::allows('restore', User::class)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data, ManageUsers $manageUsers): array {
                        $data = $manageUsers->sanitizeData($data);

                        return $data;
                    })
                    ->hidden(fn(User $user) => $user->trashed()),
                Tables\Actions\DeleteAction::make()
                    ->before(function (User $user): void {
                        $user->update([
                            'status' => Status::INACTIVE,
                        ]);

                        $user->roles()->detach();
                    }),
                Tables\Actions\RestoreAction::make()
                    ->before(function (User $user): void {
                        $user->update([
                            'status' => Status::ACTIVE,
                        ]);
                    }),
            ])
            ->defaultSort('created_at', 'asc');
    }

    public static function getSlug(): string
    {
        return strtolower(static::getPluralModelLabel());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }

    public static function getLabel(): string
    {
        return __('resources.user.singular_label');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->count();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with([
                'createdBy:id,full_name',
                'roles:id',
            ]);

        if (Gate::allows('restore', User::class)) {
            $query->withTrashed();
        }

        return auth()->user()->id !== 1
            ? $query->whereNot('id', 1)
            : $query;
    }
}

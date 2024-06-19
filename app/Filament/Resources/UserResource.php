<?php

namespace App\Filament\Resources;

use App\Enums\UserStatus;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\TextInput::make('identification_number')
                            ->required()
                            ->maxLength(20)
                            ->unique(ignoreRecord: true)
                            ->disabled(
                                fn (?Model $record): bool => $record?->id === 1
                            ),
                        Forms\Components\TextInput::make('full_name')
                            ->required()
                            ->maxLength(255)
                            ->disabled(
                                fn (?Model $record): bool => $record?->id === 1
                            ),
                        Forms\Components\DatePicker::make('birth_date')
                            ->maxDate(now())
                            ->disabled(
                                fn (?Model $record): bool => $record?->id === 1
                            ),
                    ])
                    ->columns([
                        '2xl' => 3,
                    ])
                    ->collapsible(),
                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->required()
                            ->email()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->disabled(
                                fn (?Model $record): bool => $record?->id === 1
                            ),
                        Forms\Components\TextInput::make('phone_number')
                            ->required()
                            ->maxLength(20)
                            ->disabled(
                                fn (?Model $record): bool => $record?->id === 1
                            ),
                        Forms\Components\TextInput::make('address')
                            ->maxLength(255)
                            ->disabled(
                                fn (?Model $record): bool => $record?->id === 1
                            ),
                    ])
                    ->columns([
                        '2xl' => 3,
                    ])
                    ->collapsible(),
                Forms\Components\Section::make('Account Information')
                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->default(true)
                            ->disabled(
                                fn (?Model $record): bool => $record?->id === 1
                            ),
                        Forms\Components\TextInput::make('password')
                            ->required(
                                fn (string $context): bool => $context === 'create'
                            )
                            ->minLength(8)
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(
                                fn (?string $state): ?string => Hash::make($state)
                            )
                            ->dehydrated(
                                fn (?string $state): bool => filled($state)
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
                                fn (?Model $record): bool => $record?->id === 1
                            )
                            ->dehydrated(),
                    ])
                    ->collapsible()
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('identification_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdBy.full_name')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(UserStatus::class),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hidden(Gate::allows('update', static::getModel())),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn (User $record): bool => Gate::allows('delete', [$record])
            )
            ->defaultSort('created_at', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->count();
    }

    public static function getEloquentQuery(): Builder
    {
        return auth()->user()->id !== 1
            ? parent::getEloquentQuery()->whereNot('id', 1)
            : parent::getEloquentQuery();
    }
}

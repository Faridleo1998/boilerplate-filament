<?php

namespace App\Filament\Pages;

use App\Enums\Enums\SocialNetworkEnum;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Nnjeim\World\Models\City;
use Nnjeim\World\Models\Country;
use Nnjeim\World\Models\State;

class EditSetting extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.edit-setting';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()->id === 1;
    }

    public static function getNavigationLabel(): string
    {
        return __('resources.setting.singular_label');
    }

    public static function getSlug(): string
    {
        return strtolower(__('resources.setting.singular_label'));
    }

    public function getTitle(): string
    {
        return __('resources.setting.singular_label');
    }

    public function mount(): void
    {
        $this->data = Setting::first()?->toArray();
        $this->data = $this->data ?: [];
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('application')
                            ->label(__('resources.setting.tabs.application'))
                            ->icon('heroicon-o-tv')
                            ->schema([
                                Forms\Components\Section::make(__('resources.setting.sections.company_information'))
                                    ->schema([
                                        Forms\Components\FileUpload::make('logo')
                                            ->image()
                                            ->optimize('webp')
                                            ->imageEditor()
                                            ->getUploadedFileNameForStorageUsing(
                                                fn (): string => 'logo.webp'
                                            ),
                                        Forms\Components\TextInput::make('identification_number')
                                            ->label(__('resources.setting.labels.identification_number'))
                                            ->prefixIcon('heroicon-o-identification')
                                            ->rules(['regex:/^[0-9-]*$/']),
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('resources.setting.labels.name'))
                                            ->prefixIcon('heroicon-o-home'),
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('phone_number')
                                                    ->label(__('resources.setting.labels.phone_number'))
                                                    ->tel()
                                                    ->prefixIcon('heroicon-o-phone'),
                                                Forms\Components\TextInput::make('address')
                                                    ->label(__('resources.setting.labels.address'))
                                                    ->prefixIcon('heroicon-o-map-pin'),
                                                Forms\Components\TextInput::make('email')
                                                    ->label(__('resources.setting.labels.email'))
                                                    ->email()
                                                    ->prefixIcon('heroicon-o-envelope'),
                                            ])
                                            ->columns([
                                                'lg' => 3,
                                            ]),
                                    ])
                                    ->collapsible(),
                                Forms\Components\Section::make(__('resources.setting.sections.settings'))
                                    ->schema([
                                        Forms\Components\Select::make('default_country_id')
                                            ->label(__('labels.country'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('tooltips.setting.default_country'))
                                            ->options(Country::all()->pluck('name', 'id')->toArray())
                                            ->optionsLimit(10)
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(function (Set $set): void {
                                                $set('default_state_id', null);
                                                $set('default_city_id', null);
                                            }),
                                        Forms\Components\Select::make('default_state_id')
                                            ->label(__('labels.state'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('tooltips.setting.default_state'))
                                            ->options(
                                                fn (Get $get): Collection => State::query()
                                                    ->where('country_id', $get('default_country_id'))
                                                    ->pluck('name', 'id')
                                            )
                                            ->optionsLimit(10)
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set) => $set('city_id', null)),
                                        Forms\Components\Select::make('default_city_id')
                                            ->label(__('labels.city'))
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('tooltips.setting.default_city'))
                                            ->options(
                                                fn (Get $get): Collection => City::query()
                                                    ->where('state_id', $get('default_state_id'))
                                                    ->pluck('name', 'id')
                                            )
                                            ->optionsLimit(10)
                                            ->searchable()
                                            ->preload()
                                            ->live(),
                                    ])
                                    ->columns([
                                        'sm' => 2,
                                        'xl' => 3,
                                    ])
                                    ->collapsible(),
                            ]),
                        Forms\Components\Tabs\Tab::make('social_networks')
                            ->label(__('resources.setting.tabs.social_networks'))
                            ->icon('heroicon-o-heart')
                            ->schema(function () {
                                $fields = [];
                                foreach (SocialNetworkEnum::options() as $key => $value) {
                                    $fields[] = Forms\Components\TextInput::make($key)
                                        ->label(ucfirst(strtolower($value)))
                                        ->prefixIcon("icon-brand-{$key}");
                                }

                                return $fields;
                            })
                            ->statePath('social_networks')
                            ->columns(2),
                    ]),
            ])
            ->statePath('data');
    }

    public function update(): void
    {
        try {
            $data = $this->form->getState();

            if (! $data['logo']) {
                if (file_exists(public_path('storage/logo.webp'))) {
                    Storage::disk('public')->delete('logo.webp');
                }
            }

            Setting::updateOrCreate([], $data);

            Notification::make()
                ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
                ->success()
                ->send();

            redirect(request()?->header('Referer'));
        } catch (Halt $exception) {
            return;
        }
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('Save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('update'),
        ];
    }
}

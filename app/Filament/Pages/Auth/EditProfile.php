<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('full_name')
                    ->label(__('labels.full_name'))
                    ->disabled(fn(): bool => auth()->user()->id === 1)
                    ->required(),
                $this->getEmailFormComponent()
                    ->disabled(fn(): bool => auth()->user()->id === 1),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}

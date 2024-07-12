<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SocialNetworkEnum: string implements HasLabel
{
    case WHATSAPP = 'whatsapp';
    case FACEBOOK = 'facebook';
    case INSTAGRAM = 'instagram';
    case TWITTER = 'x_twitter';
    case YOUTUBE = 'youtube';
    case LINKEDIN = 'linkedin';
    case TIKTOK = 'tiktok';
    case PINTEREST = 'pinterest';

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(function ($item) {
            return [$item->value => __($item->name)];
        })->toArray();
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::WHATSAPP => __('enums/social-network.whatsapp'),
            self::FACEBOOK => __('enums/social-network.facebook'),
            self::INSTAGRAM => __('enums/social-network.instagram'),
            self::TWITTER => __('enums/social-network.x_twitter'),
            self::YOUTUBE => __('enums/social-network.youtube'),
            self::LINKEDIN => __('enums/social-network.linkedin'),
            self::TIKTOK => __('enums/social-network.tiktok'),
            self::PINTEREST => __('enums/social-network.pinterest'),
        };
    }
}

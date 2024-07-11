<?php

namespace App\Models;

use App\Enums\Status;
use App\Traits\Models\HasCreatedBy;
use App\Traits\Models\Relations\UserRelations;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasCreatedBy, HasFactory, HasRoles, Notifiable;
    use UserRelations;

    protected $fillable = [
        'identification_number',
        'full_name',
        'birth_date',
        'phone_number',
        'address',
        'status',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->status === Status::ACTIVE;
    }

    public function getFilamentName(): string
    {
        return "{$this->full_name}";
    }

    // Casts
    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

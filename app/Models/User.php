<?php

namespace App\Models;

use App\Enums\UserStatus;
use App\Traits\Models\HasCreatedBy;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasCreatedBy, HasFactory, HasRoles, Notifiable;

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
        return $this->status === UserStatus::ACTIVE;
    }

    public function getFilamentName(): string
    {
        return "{$this->full_name}";
    }

    // Relationships
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected function casts(): array
    {
        return [
            'status' => UserStatus::class,
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

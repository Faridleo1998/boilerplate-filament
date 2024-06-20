<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_user');
    }

    public function view(User $user): bool
    {
        $canView = $user->can('view_user');
        $canNotUpdate = Gate::denies('update', User::class);

        return $canView && $canNotUpdate;
    }

    public function create(User $user): bool
    {
        return $user->can('create_user');
    }

    public function update(User $user): bool
    {
        return $user->can('update_user');
    }

    public function delete(User $user, User $record): bool
    {
        $canDelete = $user->can('delete_user');
        $isNotSelf = $record->id !== $user->id;
        $isNotSuperAdmin = $record->id !== 1;

        return $canDelete && $isNotSelf && $isNotSuperAdmin;
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_user');
    }
}

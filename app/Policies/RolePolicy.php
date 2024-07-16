<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_shield::role');
    }

    public function view(User $user, Role $role): bool
    {
        $canView = $user->can('view_shield::role');
        $canNotUpdate = Gate::denies('update', [$role]);

        return $canView && $canNotUpdate;
    }

    public function create(User $user): bool
    {
        return $user->can('create_shield::role');
    }

    public function update(User $user, Role $role): bool
    {
        $isNotSuperAdmin = $role->id !== 1;
        $userHasRole = $user->hasRole($role->name);
        $canUpdate = $user->can('update_shield::role');

        return $canUpdate && $isNotSuperAdmin && ! $userHasRole;
    }

    public function delete(User $user, Role $role): bool
    {
        $canDelete = $user->can('delete_shield::role');
        $isNotSuperAdmin = $role->id !== 1;
        $userHasRole = $user->hasRole($role->name);

        return $canDelete && $isNotSuperAdmin && ! $userHasRole;
    }
}

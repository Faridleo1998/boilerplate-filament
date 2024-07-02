<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_customer');
    }

    public function view(User $user): bool
    {
        return $user->can('view_customer');
    }

    public function create(User $user): bool
    {
        return $user->can('create_customer');
    }

    public function update(User $user): bool
    {
        return $user->can('update_customer');
    }

    public function delete(User $user): bool
    {
        return $user->can('delete_customer');
    }
}

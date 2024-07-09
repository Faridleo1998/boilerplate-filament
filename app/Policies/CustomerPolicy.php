<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_customer');
    }

    public function view(User $user): bool
    {
        $canView = $user->can('view_customer');
        $canNotUpdate = Gate::denies('update', Customer::class);

        return $canView && $canNotUpdate;
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

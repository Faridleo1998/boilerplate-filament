<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentMethodPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_any_payment::method');
    }

    public function create(User $user): bool
    {
        return $user->can('create_payment::method');
    }

    public function update(User $user): bool
    {
        return $user->can('update_payment::method');
    }

    public function delete(User $user): bool
    {
        return $user->can('delete_payment::method');
    }
}

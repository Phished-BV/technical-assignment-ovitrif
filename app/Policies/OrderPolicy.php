<?php

namespace App\Policies;

use App\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        // TODO proper logic could be implemented here
        return $user->name === 'admin';
    }
}

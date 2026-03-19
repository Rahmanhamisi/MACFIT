<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function __construct() {}

    /**
     * View all roles
     */
    public function viewAny(User $user)
    {
        return $user->role?->id === 1; // Admin only
    }

    /**
     * View a specific role
     */
    public function view(User $user, Role $role)
    {
        return $user->role?->id === 1; // Admin only
    }

    /**
     * Create role
     */
    public function create(User $user)
    {
        return $user->role?->id === 1; // Admin only
    }

    /**
     * Update role
     */
    public function update(User $user, Role $role)
    {
        return $user->role?->id === 1; // Admin only
    }

    /**
     * Delete role
     */
    public function delete(User $user, Role $role)
    {
        return $user->role?->id === 1; // Admin only
    }
}
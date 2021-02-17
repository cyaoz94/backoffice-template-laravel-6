<?php

namespace App\Policies;


use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the role.
     *
     * @param \App\User $user
     * @param Role $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        return $role->name === 'Super Admin'
            ? Response::deny('Super admins cannot be updated.')
            : true;
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param \App\User $user
     * @param Role $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        return $role->name === 'Super Admin'
            ? Response::deny('Super admins cannot be deleted.')
            : true;
    }

    public function assignRole(User $user, Role $role)
    {
        return $role->name === 'Super Admin'
            ? Response::deny('Super admins cannot be created.')
            : true;
    }
}

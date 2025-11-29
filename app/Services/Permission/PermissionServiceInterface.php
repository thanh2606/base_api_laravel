<?php

namespace App\Services\Permission;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

interface PermissionServiceInterface
{
    /**
     * @param Role $role
     * @param Permission $permission
     * @return bool
     */
    public function assignPermissionToRole(Role $role, Permission $permission): bool;

    /**
     * @param Role $role
     * @param Permission $permission
     * @return bool
     */
    public function revokePermissionFromRole(Role $role, Permission $permission): bool;

    /**
     * @return Collection
     */
    public function getAll(): Collection;
}

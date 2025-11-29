<?php

namespace App\Services\Permission;

use App\Models\Permission;
use App\Models\Role;
use App\Repositories\Permission\PermissionRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class PermissionService extends BaseService implements PermissionServiceInterface
{
    public function __construct(
        protected readonly PermissionRepository $permissionRepository
    ) {
        $this->repository = $permissionRepository;
    }

    /**
     * @param Role $role
     * @param Permission $permission
     * @return bool
     */
    public function assignPermissionToRole(Role $role, Permission $permission): bool
    {
        if ($role->hasPermission($permission->name)) {
            $role->givePermission($permission);

            return true;
        }

        return false;
    }

    /**
     * @param Role $role
     * @param Permission $permission
     * @return bool
     */
    public function revokePermissionFromRole(Role $role, Permission $permission): bool
    {
        $role->revokePermission($permission);

        return true;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }
}

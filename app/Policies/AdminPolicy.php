<?php

namespace App\Policies;

use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    public function before(Admin $admin, string $ability): bool
    {
        if ($admin->isSuperAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->hasPermission('admin-view');
    }

    public function view(Admin $admin, Admin $model): bool
    {
        return $admin->hasPermission('admin-view');
    }

    public function create(Admin $admin): bool
    {
        return $admin->hasPermission('admin-create');
    }

    public function update(Admin $admin, Admin $model): bool
    {
        return $admin->hasPermission('admin-edit');
    }

    public function delete(Admin $admin, Admin $model): bool
    {
        return $admin->hasPermission('admin-delete') && $admin->id !== $model->id;
    }

    // Không cho phép admin tự xóa mình
    public function forceDelete(Admin $admin, Admin $model): bool
    {
        return $this->delete($admin, $model);
    }
}

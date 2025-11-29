<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'status',
    ];

    /**
     * @return HasMany<Admin, Role>
     */
    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class);
    }

    /**
     * Relationship với Permission qua pivot table
     *
     * @return BelongsToMany<Permission, Role, Pivot>
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    /**
     * Gán quyền cho role
     *
     * @param Permission $permission
     * @return void
     */
    public function givePermission(Permission $permission): void
    {
        $this->permissions()->attach($permission->id);
    }

    /**
     * Gán nhiều quyền cho role
     *
     * @param array $permissions
     * @return void
     */
    public function givePermissions(array $permissions): void
    {
        $this->permissions()->attach($permissions);
    }

    /**
     * Thu hồi quyền
     *
     * @param Permission $permission
     * @return void
     */
    public function revokePermission(Permission $permission): void
    {
        $this->permissions()->detach($permission->id);
    }

    /**
     * Thu hồi tất cả quyền
     *
     * @return void
     */
    public function revokeAllPermissions(): void
    {
        $this->permissions()->detach();
    }

    /**
     * Kiểm tra role có quyền cụ thể không
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }
}

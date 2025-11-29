<?php

namespace App\Models;

use App\Enums\EnumStatus;
use App\Traits\ImageRelationTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $status
 * @property string $phone
 * @property string $address
 * @property int $image_id
 * @property int $role_id
 * @property string $image
 * @property string $remember_token
 * @property Role $role
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class Admin extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;
    use ImageRelationTrait;

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'phone',
        'address',
        'image_id',
        'role_id',
    ];

    protected $appends = [
        'image',
    ];

    protected $hidden = [
       'password',
       'remember_token',
    ];

    protected $casts = [
        'status' => EnumStatus::class,
    ];

    /**
     * @return BelongsTo<Role, Admin>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->permissions()->where('name', $permission)->exists();
    }

    /**
     * @param string $action
     * @return bool
     */
    public function hasAction(string $action): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->permissions()->where('action', $action)->exists();
    }

    public function isSuperAdmin(): bool
    {
        return $this->role && $this->role->name === 'Super Admin';
    }

    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }
}

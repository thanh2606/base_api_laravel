<?php

namespace App\Repositories\Permission;

use App\Models\Permission;
use Prettus\Repository\Eloquent\BaseRepository;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    public function model(): string
    {
        return Permission::class;
    }
}

<?php

namespace App\Services\Role;

use App\Repositories\Role\RoleRepository;
use App\Services\BaseService;

class RoleService extends BaseService implements RoleServiceInterface
{
    /**
     * @param RoleRepository $roleRepository
     */
    public function __construct(
        protected readonly RoleRepository $roleRepository
    ) {
        $this->repository = $roleRepository;
    }
}

<?php

namespace App\Services\Admin;

interface AdminServiceInterface
{
    /**
     * @param int $id
     * @param string $password
     * @return bool
     */
    public function changePassword(int $id, string $password): bool;

    /**
     * @param array $ids
     * @return void
     */
    public function multiDelete(array $ids): void;
}

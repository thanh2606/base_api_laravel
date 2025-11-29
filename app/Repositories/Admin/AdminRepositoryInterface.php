<?php

namespace App\Repositories\Admin;

interface AdminRepositoryInterface
{
    public function paginate(array $filters = []);
}

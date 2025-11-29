<?php

namespace App\Services\Admin;

use App\Repositories\Admin\AdminRepository;
use App\Services\BaseService;
use App\Traits\UploadTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Prettus\Validator\Exceptions\ValidatorException;

class AdminService extends BaseService implements AdminServiceInterface
{
    use UploadTrait;

    /**
     * @param AdminRepository $adminRepository
     */
    public function __construct(
        protected readonly AdminRepository $adminRepository
    ) {
        $this->repository = $adminRepository;
    }

    /**
     * @throws ValidatorException
     */
    public function changePassword(int $id, string $password): bool
    {
        return $this->adminRepository->update(['password' => Hash::make($password)], $id);
    }

    /**
     * @param array $ids
     * @return void
     */
    public function multiDelete(array $ids): void
    {
        $records = $this->adminRepository->getByIds($ids);

        if ($records->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($records) {
            foreach ($records as $record) {
                if (!empty($record->media)) {
                    $this->deleteByPath($record->media->path);
                    $record->media->delete();
                }

                $record->delete();
            }
        });
    }

    /**
     * @param string|null $search
     * @param int|null $limit
     * @return LengthAwarePaginator
     */
    public function search(?string $search = null, ?int $limit = null): LengthAwarePaginator
    {
        if (empty($search)) {
            return $this->adminRepository
                ->paginate($limit);
        }

        return $this->adminRepository->scopeQuery(function ($query) use ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        })->paginate($limit);
    }
}

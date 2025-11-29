<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Exceptions\ValidatorException;

class ProductService extends BaseService implements ProductServiceInterface
{
    /**
     * @param ProductRepository $productRepository
     */
    public function __construct(
        protected readonly ProductRepository $productRepository
    ) {
        $this->repository = $this->productRepository;
    }

    /**
     * @param string|null $keyword
     * @param int|null $limit
     * @return LengthAwarePaginator
     */
    public function search(?string $keyword = null, ?int $limit = null): LengthAwarePaginator
    {
        if (empty($keyword)) {
            return $this->repository->paginate($limit);
        }

        return $this->repository
            ->scopeQuery(function ($query) use ($keyword) {
                return $query->where('title', 'LIKE', "%{$keyword}%");
            })
            ->paginate($limit);
    }

    /**
     * @param array $data
     * @return Product
     *
     * @throws ValidatorException
     */
    public function createVirtualProduct(array $data): Product
    {
        return $this->repository->create($data);
    }

    /**
     * @param array $data
     * @return Product
     *
     * @throws ValidatorException
     */
    public function createVariableProduct(array $data): Product
    {
        return $this->repository->create($data);
    }

    /**
     * @param array $data
     * @return Product|Model
     */
    public function createSimpleProduct(array $data): Product|Model
    {
        return DB::transaction(function () use ($data) {
            $product = $this->repository->create($data);

            if (!empty($data['gallery_ids'])) {
                $product->galleries()->sync($data['gallery_ids']);
            }

            if (!empty($data['category_ids'])) {
                $product->categories()->sync($data['category_ids']);
            }

            if (!empty($data['tag_ids'])) {
                $product->tags()->sync($data['tag_ids']);
            }

            return $product->load(['galleries', 'categories', 'tags']);
        });
    }
}

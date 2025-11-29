<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

interface ProductServiceInterface
{
    /**
     * @param array $data
     * @return Product
     */
    public function createVirtualProduct(array $data): Product;

    /**
     * @param array $data
     * @return Product
     */
    public function createVariableProduct(array $data): Product;

    /**
     * @param array $data
     * @return Product|Model
     */
    public function createSimpleProduct(array $data): Product|Model;
}

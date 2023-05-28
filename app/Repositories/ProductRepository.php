<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    /**
     * Returns all products
     * @return Collection
     */
    public function allProducts(  ): Collection
    {
        return Product::all();
    }
}
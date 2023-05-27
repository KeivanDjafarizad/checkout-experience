<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function allProducts(  ): Collection
    {
        return Product::all();
    }
}
<?php

namespace App\Models\ValueObjects;

use App\Models\Product;

class CartItem
{
    public function __construct(
        public string $productSku,
        public int $quantity,
        public readonly Product $product,
        public readonly float $total,
        public readonly ?float $totalDiscounted = null,
    ) { }

}
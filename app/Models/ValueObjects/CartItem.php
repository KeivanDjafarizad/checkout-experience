<?php

namespace App\Models\ValueObjects;

use App\Models\Product;

final class CartItem
{
    /**
     * Creates a new cart item already contained in the cart
     *
     * @param string $productSku
     * @param int $quantity
     * @param Product $product
     * @param float $total
     * @param float|null $totalDiscounted
     */
    public function __construct(
        public readonly string $productSku,
        public readonly int $quantity,
        public readonly Product $product,
        public readonly float $total,
        public readonly ?float $totalDiscounted = null,
    ) { }

}
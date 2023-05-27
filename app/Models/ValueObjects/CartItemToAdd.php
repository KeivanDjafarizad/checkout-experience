<?php

namespace App\Models\ValueObjects;

final class CartItemToAdd
{
    public function __construct(
        public readonly string $productSku,
        public readonly int $quantity,
    ) { }

    public function toArray(): array
    {
        return [
            'product_sku' => $this->productSku,
            'quantity' => $this->quantity,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['product_sku'],
            $data['quantity'],
        );
    }
}
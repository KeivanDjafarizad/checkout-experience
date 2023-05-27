<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Order;

class OrderRespository
{
    public function createOrder( Cart $cart, string $paymentId ): Order
    {
        return Order::create([
            'cart_id' => $cart->id,
            'email' => $cart->email,
            'items' => $cart->items,
            'first_name' => $cart->first_name,
            'last_name' => $cart->last_name,
            'tax_code' => $cart->tax_code,
            'tax_number' => $cart->tax_number,
            'payment_id' => $paymentId,
            'total' => $cart->total,
            'status' => Order::STATUS_PENDING,
        ]);
    }

    public function getOrderByPaymentId( string|null $paymentId ): Order|null
    {
        return Order::where('payment_id', $paymentId)->first();
    }
}
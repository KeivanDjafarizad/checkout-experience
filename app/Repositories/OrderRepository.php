<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Order;

class OrderRepository
{
    /**
     * Creates an order from a cart
     * @param Cart $cart
     * @return Order
     */
    public function createOrder( Cart $cart ): Order
    {
        return Order::create([
            'cart_id' => $cart->id,
            'email' => $cart->email,
            'items' => $cart->items,
            'first_name' => $cart->first_name,
            'last_name' => $cart->last_name,
            'tax_code' => $cart->tax_code,
            'tax_number' => $cart->tax_number,
            'total' => $cart->total,
            'status' => Order::STATUS_PENDING,
        ]);
    }

    /**
     * Returns an order by its payment id
     * @param string|null $paymentId
     * @return Order|null
     */
    public function getOrderByPaymentId( string|null $paymentId ): Order|null
    {
        return Order::where('payment_id', $paymentId)->first();
    }

    /**
     * Returns an order by its id
     * @param string $decryptString
     * @return mixed
     */
    public function getOrderById( string $decryptString )
    {
        return Order::find($decryptString);
    }
}
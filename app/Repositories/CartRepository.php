<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\DataTransferObjects\CheckoutDetails;
use App\Models\Product;
use App\Models\ValueObjects\CartItemToAdd;

class CartRepository
{
    public static function getCurrentCart(  ): Cart
    {
        $cartId = session('cart_id');
        if (
            empty($cartId)
            || !Cart::where('session_id', $cartId)->exists()
        ) {
            $cart = new Cart();
            $cart->session_id = uniqid();
            $cart->save();
            session()->put('cart_id', $cart->session_id);
            return $cart;
        }
        return Cart::where('session_id', $cartId)->first();
    }

    public function editProductInCart( Cart $cart, CartItemToAdd $item ): void
    {
        $items = $cart->items;
        if($item->quantity > Product::where('sku', $item->productSku)->first()->stock_qty) {
            throw new \Exception('Not enough stock');
        }
        if($items[$item->productSku] ?? false) {
            $items[$item->productSku] += $item->quantity;
        } else {
            $items[$item->productSku] = $item->quantity;
        }

        if($items[$item->productSku] <= 0) {
            unset($items[$item->productSku]);
        }

        $cart->items = $items;
        $cart->updateTotal();
        $cart->save();
    }

    public function addDetailsToCart( Cart $cart, CheckoutDetails $details ): void
    {
        $cart->update($details->toCartUpdateArray());
    }
}
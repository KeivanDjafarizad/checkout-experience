<?php

namespace App\Models;

use App\Models\ValueObjects\CartItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'items',
        'total',
        'first_name',
        'last_name',
        'email',
        'nation',
        'tax_number',
        'tax_code',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function coupon(  ): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function order(  ): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Returns the number of items in the cart
     * @return int
     */
    public function itemsNumber(  ): int
    {
        return isset($this->items) ? array_sum($this->items) : 0;
    }

    /**
     * Updates the total of the cart
     * @return void
     */
    public function updateTotal(  ): void
    {
        $skus = array_keys($this->items);
        $products = Product::whereIn('sku', $skus)->get();
        $total = 0;
        foreach($products as $product) {
            if(
                $this->coupon
                && in_array($product->id, $this->coupon->associated_product_ids)
                && $this->total >= $this->coupon->min_price
                && $this->total <= $this->coupon->max_price
            ) {
                $total += ($product->price - $product->price * $this->coupon->amount) * $this->items[$product->sku];
                continue;
            }
            $total += $product->price * $this->items[$product->sku];
        }
        $this->total = $total;
        $this->save();
    }

    /**
     * Returns the cart items as an array of CartItem objects
     * @return array<CartItem>
     */
    public function items(  ): array
    {
        if(!isset($this->items)) {
            return [];
        }
        $skus = array_keys($this->items);
        $products = Product::whereIn('sku', $skus)->get();
        $items = [];
        foreach($products as $product) {
            if(
                $this->coupon
                && in_array($product->id, $this->coupon->associated_product_ids)
                && $this->total > $this->coupon->min_price
                && $this->total <= $this->coupon->max_price
            ) {
                $discountedPrice = $product->price - ($product->price * $this->coupon->amount);
            }
            $items[] = new CartItem(
                $product->sku,
                $this->items[$product->sku],
                $product,
                $product->price * $this->items[$product->sku],
                $discountedPrice ?? null,
            );
        }
        $this->updateTotal();
        return $items;
    }
}

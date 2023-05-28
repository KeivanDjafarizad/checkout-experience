<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';

    protected $casts = [
        'items' => 'array',
    ];

    protected $fillable = [
        'cart_id',
        'email',
        'items',
        'first_name',
        'last_name',
        'tax_code',
        'tax_number',
        'payment_id',
        'total',
        'status',
    ];

    public function cart(  )
    {
        return $this->belongsTo(Cart::class);
    }
}

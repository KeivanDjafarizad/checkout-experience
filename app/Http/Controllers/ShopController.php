<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) { }

    public function index(  )
    {
        $products = $this->productRepository->allProducts();
        return view('shop.index', compact('products'));
    }
}

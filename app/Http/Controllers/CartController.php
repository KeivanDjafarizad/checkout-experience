<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCoupon;
use App\Http\Requests\EditCart;
use App\Models\Coupon;
use App\Models\ValueObjects\CartItemToAdd;
use App\Repositories\CartRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private readonly CartRepository $cartRepository,
    ) { }

    /**
     * Shows the cart page
     * @return View
     */
    public function index(  ): View
    {
        $cart = $this->cartRepository::getCurrentCart();
        $cartItems = $cart->items();
        $total = $cart->total;
        return view(
            'cart.index',
            compact('cartItems', 'total', 'cart')
        );
    }

    /**
     * Adds or removes a product from the cart
     * @param EditCart $request
     * @return JsonResponse
     */
    public function edit( EditCart $request ): JsonResponse
    {
        $cart = $this->cartRepository::getCurrentCart();
        $itemToAdd = CartItemToAdd::fromArray($request->validated());
        try {
            $this->cartRepository->editProductInCart($cart, $itemToAdd);
            return response()->json([
                'data' => $cart->items,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Adds a coupon to the cart
     * @param AddCoupon $request
     * @return RedirectResponse
     */
    public function addCoupon( AddCoupon $request ): RedirectResponse
    {
        $cart = $this->cartRepository::getCurrentCart();
        $coupon = Coupon::where('code', $request->validated()['coupon'])->first();
        if(!$coupon || $cart->total < $coupon->min_price || $cart->total > $coupon->max_price) {
            return redirect()
                ->back()
                ->withErrors(['coupon' => 'Coupon not applicable']);
        }
        $cart->coupon()->associate($coupon);
        $cart->updateTotal();
        $cart->save();
        return redirect()
            ->back();
    }

    /**
     * Removes a coupon from the cart
     * @return RedirectResponse
     */
    public function removeCoupon(  ): RedirectResponse
    {
        $cart = $this->cartRepository::getCurrentCart();
        $cart->coupon_id = null;
        $cart->updateTotal();
        $cart->save();
        return redirect()
            ->back();
    }
}

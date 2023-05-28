<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutDetails;
use App\Http\Requests\ManagePayment;
use App\Models\Order;
use App\Repositories\CartRepository;
use App\Repositories\OrderRespository;
use App\StripeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartRepository $cartRepository,
        private readonly OrderRespository $orderRepository,
    ) { }

    public function index(  ): View
    {
        $cart = $this->cartRepository->getCurrentCart();
        return view('checkout.index', compact('cart'));
    }

    public function addDetails( CheckoutDetails $request ): RedirectResponse
    {
        $details = \App\Models\DataTransferObjects\CheckoutDetails::fromRequest($request);
        $cart = $this->cartRepository->getCurrentCart();
        $this->cartRepository->addDetailsToCart($cart, $details);
        $cart->refresh();
        return redirect()
            ->route('checkout.payment');
    }

    public function payment(  ): View
    {
        $cart = $this->cartRepository->getCurrentCart();
        return view('checkout.payment', compact('cart'));
    }

    public function managePayment( ManagePayment $request ): RedirectResponse
    {
        $payment = match($request->validated()['payment_type']) {
            'stripe' => new StripeService(config('services.stripe.secret')),
        };

        $order = $this->orderRepository->createOrder(
            $this->cartRepository->getCurrentCart(),
        );

        $response = $payment->prepareOrder($this->cartRepository->getCurrentCart()->items(), $order);

        $order->payment_id = $response->paymentId;
        $order->save();

        return redirect()
            ->to($response->redirectUrl);
    }

    public function cancelOrder(  ): RedirectResponse
    {
        $cart = $this->cartRepository::getCurrentCart();
        $cart->order->destroy();
        return redirect()
            ->route('checkout.payment');
    }

    public function paymentSuccess( Request $request, string $orderId ): View
    {
        $order = Order::where('id', Crypt::decryptString($orderId))->firstOrFail();
        $order->status = Order::STATUS_PAID;
        $order->save();

        $cart = $this->cartRepository->getCurrentCart();
        $cart->forceDelete();

        return view('checkout.success');
    }
}

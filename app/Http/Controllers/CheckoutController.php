<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutDetails;
use App\Http\Requests\ManagePayment;
use App\Models\Order;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Services\StripeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Stripe\Exception\ApiErrorException;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartRepository  $cartRepository,
        private readonly OrderRepository $orderRepository,
    ) { }

    /**
     * Shows the checkout page
     * @return View
     */
    public function index(  ): View
    {
        $cart = $this->cartRepository->getCurrentCart();
        return view('checkout.index', compact('cart'));
    }

    /**
     * Adds the details to the cart
     * @param CheckoutDetails $request
     * @return RedirectResponse
     */
    public function addDetails( CheckoutDetails $request ): RedirectResponse
    {
        $details = \App\Models\DataTransferObjects\CheckoutDetails::fromRequest($request);
        $cart = $this->cartRepository->getCurrentCart();
        $this->cartRepository->addDetailsToCart($cart, $details);
        $cart->refresh();
        return redirect()
            ->route('checkout.payment');
    }

    /**
     * Shows the payment page
     * @return View|RedirectResponse
     */
    public function payment(  ): View|RedirectResponse
    {
        $cart = $this->cartRepository->getCurrentCart();
        if(!$cart->first_name || !$cart->last_name || !$cart->email || !$cart->nation) {
            return redirect()
                ->route('checkout.index');
        }
        return view('checkout.payment', compact('cart'));
    }

    /**
     * Manages the payment depending on the payment type
     * @param ManagePayment $request
     * @return RedirectResponse
     * @throws ApiErrorException
     */
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

    /**
     * Cancels the order
     * @param string $orderId
     * @return RedirectResponse
     */
    public function cancelOrder( string $orderId ): RedirectResponse
    {
        $order = Order::where('id', Crypt::decryptString($orderId))->firstOrFail();

        return redirect()
            ->route('checkout.payment');
    }

    /**
     * Shows the success page
     * @param Request $request
     * @param string $orderId
     * @return View
     */
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

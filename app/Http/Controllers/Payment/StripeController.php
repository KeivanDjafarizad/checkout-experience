<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\CartRepository;
use App\Repositories\OrderRespository;
use App\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class StripeController extends Controller
{
    public function __construct(
        private readonly StripeService $stripeService,
        private readonly OrderRespository $orderRepository,
        private readonly CartRepository $cartRepository,
    ) { }

    public function success( Request $request )
    {
        try {
            $this->stripeService->confirmOrder($request->query('payment_intent'));
        } catch (\Exception $e) {
            return redirect()
                ->route('payment.cancel');
        }

        $order = $this->orderRepository->getOrderByPaymentId($request->query('payment_intent'));

        return redirect()
            ->route('checkout.success', ['order' => Crypt::encryptString($order->id)]);
    }
}

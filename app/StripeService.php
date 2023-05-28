<?php

namespace App;

use App\Models\DataTransferObjects\Payment\PaymentServiceRedirect;
use App\Models\Order;
use Illuminate\Support\Facades\Crypt;

class StripeService
{
    public function __construct(
        private readonly string $secretKey,
    ) { }

    public function prepareOrder( array $itemList, Order $order ): PaymentServiceRedirect
    {
        \Stripe\Stripe::setApiKey($this->secretKey);
        $items = [];
        foreach($itemList as $item) {
            $amount = $item->totalDiscounted ?? $item->total;
            $items[] = [
                'name' => $item->product->name,
                'quantity' => $item->quantity,
                'currency' => 'eur',
                'amount' => (int) number_format($amount, 2) * 100,
            ];
        }
        $stripeCheckoutSession = \Stripe\Checkout\Session::create([
            'line_items' => $items,
            'mode' => 'payment',
            'success_url' => route('payment.stripe.success', ['order' => Crypt::encryptString($order->id)]),
            'cancel_url' => route('payment.cancel', ['order' => Crypt::encryptString($order->id)]),
        ]);

        return new PaymentServiceRedirect(
            redirectUrl: $stripeCheckoutSession->url,
            paymentId: $stripeCheckoutSession->id,
        );
    }

    public function confirmOrder( string $paymentId ): void
    {
        \Stripe\Stripe::setApiKey($this->secretKey);
        try {
            $stripeCheckoutSession = \Stripe\Checkout\Session::retrieve($paymentId);
            return;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new \Exception('Payment not found');
        }
    }

}
<?php

namespace App;

use App\Models\DataTransferObjects\Payment\PaymentServiceRedirect;

class StripeService
{
    public function __construct(
        private readonly string $secretKey,
    ) { }

    public function prepareOrder( array $itemList ): PaymentServiceRedirect
    {
        \Stripe\Stripe::setApiKey($this->secretKey);
        $items = [];
        foreach($itemList as $item) {
            $items[] = [
                'price_data' => $item->product->totalDiscounted ?? $item->product->total,
                'quantity' => $item->quantity,
            ];
        }
        $stripeCheckoutSession = \Stripe\Checkout\Session::create([
            'line_items' => $items,
            'mode' => 'payment',
            'success_url' => route('payment.stripe.success'),
            'cancel_url' => route('payment.cancel'),
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
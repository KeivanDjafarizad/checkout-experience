<?php

namespace App\Services;

use App\Models\DataTransferObjects\Payment\PaymentServiceRedirect;
use App\Models\Order;
use Illuminate\Support\Facades\Crypt;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    public function __construct(
        private readonly string $secretKey,
    ) { }

    /**
     * Prepares a checkout session for Stripe from an order
     * @param array $itemList
     * @param Order $order
     * @return PaymentServiceRedirect
     * @throws ApiErrorException
     */
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

    /**
     * Confirms an order from a payment id via Stripe Checkout Session API
     * @param string $paymentId
     * @return void
     * @throws \Exception
     */
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
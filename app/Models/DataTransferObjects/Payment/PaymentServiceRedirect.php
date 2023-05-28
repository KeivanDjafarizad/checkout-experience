<?php

namespace App\Models\DataTransferObjects\Payment;

final class PaymentServiceRedirect
{
    /**
     * Creates a standard redirect response to the payment service
     *
     * @param string $redirectUrl
     * @param string $paymentId
     */
    public function __construct(
        public readonly string $redirectUrl,
        public readonly string $paymentId
    ) { }
}
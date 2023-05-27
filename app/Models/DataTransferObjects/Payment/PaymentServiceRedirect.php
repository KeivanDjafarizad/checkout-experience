<?php

namespace App\Models\DataTransferObjects\Payment;

final class PaymentServiceRedirect
{
    public function __construct(
        public readonly string $redirectUrl,
        public readonly string $paymentId
    ) { }
}
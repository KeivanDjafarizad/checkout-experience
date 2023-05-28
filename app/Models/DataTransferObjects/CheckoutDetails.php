<?php

namespace App\Models\DataTransferObjects;

final class CheckoutDetails
{
    /**
     * Creates a new checkout details object to be added to the cart
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $nation
     * @param string|null $fiscalTaxNumber
     * @param string|null $fiscalCodeNumber
     * @param bool $newsletter
     * @param bool $invoice
     */
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly string $nation,
        public readonly ?string $fiscalTaxNumber,
        public readonly ?string $fiscalCodeNumber,
        public readonly bool $newsletter = false,
        public readonly bool $invoice = false,
    ) {
        if($invoice && empty($fiscalTaxNumber) && empty($fiscalCodeNumber)) {
            throw new \InvalidArgumentException('Fiscal tax number or fiscal code number must be provided');
        }
    }

    public static function fromRequest( \App\Http\Requests\CheckoutDetails $request ): self
    {
        return new self(
            $request->input('first-name'),
            $request->input('last-name'),
            $request->input('email'),
            $request->input('nation'),
            $request->input('fiscal-tax-number'),
            $request->input('fiscal-code-number'),
            $request->input('newsletter') ?? false,
            $request->input('invoice') ?? false,
        );
    }

    public function toCartUpdateArray(  ): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'nation' => $this->nation,
            'tax_number' => $this->invoice ? $this->fiscalTaxNumber : null,
            'tax_code' => $this->invoice ? $this->fiscalCodeNumber : null,
        ];
    }
}
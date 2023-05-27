<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class EditCart extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'product_sku' => 'required|string|exists:products,sku',
            'quantity' => 'required|integer',
        ];
    }

    public function failedValidation( Validator $validator )
    {
        throw new ValidationException(
            $validator,
            response()
                ->json([
                    'errors' => $validator->errors(),
                ], 422)
        );
    }
}

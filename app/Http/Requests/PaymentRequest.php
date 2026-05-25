<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'total_fee'   => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'hostel'      => 'nullable|boolean',
            'transport'   => 'nullable|boolean',
            'meal'        => 'nullable|boolean',
        ];
    }
}
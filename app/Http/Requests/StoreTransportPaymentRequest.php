<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransportPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_transport_id' => 'required|exists:student_transports,id',
            'amount'               => 'required|numeric|min:1',
            'payment_date'         => 'required|date',
            'payment_mode'         => 'nullable|string|max:50',
            'note'                 => 'nullable|string'
        ];
    }
}
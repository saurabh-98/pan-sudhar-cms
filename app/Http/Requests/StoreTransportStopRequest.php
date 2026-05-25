<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransportStopRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'route_id'     => 'required|exists:transport_routes,id',
            'stop_name'    => 'required|string|max:255',
            'stop_order'   => 'nullable|integer|min:1',
            'pickup_time'  => 'nullable|date_format:H:i',
        ];
    }
}
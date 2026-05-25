<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentTransportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'route_id'   => 'required|exists:transport_routes,id',
            'stop_id'    => 'required|exists:transport_stops,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'fee'        => 'required|numeric|min:0',
        ];
    }
}
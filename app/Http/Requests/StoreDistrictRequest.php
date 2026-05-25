<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDistrictRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'state_id' => ['required', 'exists:states,id'],
            'name' => ['required', 'string', 'max:100']
        ];
    }

    public function messages(): array
    {
        return [
            'state_id.required' => 'Please select state',
            'state_id.exists' => 'Invalid state selected',
            'name.required' => 'District name is required',
        ];
    }
}
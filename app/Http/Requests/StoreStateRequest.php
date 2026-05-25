<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:states,name']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'State name is required',
            'name.unique' => 'State already exists'
        ];
    }
}
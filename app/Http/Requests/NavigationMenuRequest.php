<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NavigationMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'   => 'required|string|max:255',
            'url'    => 'required|string|max:255',
            'order'  => 'nullable|integer',
            'status' => 'nullable|boolean',
        ];
    }
}
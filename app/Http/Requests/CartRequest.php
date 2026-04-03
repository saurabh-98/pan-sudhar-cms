<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ✅ important
    }

    public function rules(): array
    {
        return [
            'menu_id' => 'required|exists:menus,id'
        ];
    }

    public function messages(): array
    {
        return [
            'menu_id.required' => 'Menu item is required',
            'menu_id.exists' => 'Invalid menu item selected'
        ];
    }
}
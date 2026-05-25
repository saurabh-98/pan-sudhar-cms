<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DesignationRequest extends FormRequest
{
    /* ================= AUTHORIZE ================= */
    public function authorize(): bool
    {
        return true; // ✅ MUST BE TRUE
    }

    /* ================= RULES ================= */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:designations,name'
        ];
    }

    /* ================= MESSAGES ================= */
    public function messages(): array
    {
        return [
            'name.required' => 'Designation name is required',
            'name.unique'   => 'Designation already exists'
        ];
    }
}
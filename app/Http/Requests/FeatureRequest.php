<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'icon' => ['nullable','string','max:50'],
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'is_active' => ['nullable','boolean'],
        ];
    }
}
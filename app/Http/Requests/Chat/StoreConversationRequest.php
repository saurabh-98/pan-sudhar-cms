<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class StoreConversationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [

            'admin_id' => [
                'nullable',
                'exists:users,id',
            ],

        ];
    }

    /**
     * Custom messages.
     */
    public function messages(): array
    {
        return [

            'admin_id.exists' => 'Selected admin does not exist.',

        ];
    }

    /**
     * Sanitized data
     */
    public function validatedData(): array
    {
        return $this->validated();
    }
}
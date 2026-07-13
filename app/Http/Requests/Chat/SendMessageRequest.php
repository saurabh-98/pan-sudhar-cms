<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
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

            'message' => [
                'nullable',
                'string',
                'max:5000',
                'required_without:attachment',
            ],

            'attachment' => [
                'nullable',
                'file',
                'required_without:message',
                'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,zip',
                'max:10240', // 10MB
            ],

        ];
    }

    /**
     * Custom messages.
     */
    public function messages(): array
    {
        return [

            'message.required_without' =>
                'Please enter a message or upload a file.',

            'attachment.required_without' =>
                'Please upload a file or enter a message.',

            'attachment.mimes' =>
                'Only image, PDF, Word, Excel and ZIP files are allowed.',

            'attachment.max' =>
                'Maximum file size is 10 MB.',

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
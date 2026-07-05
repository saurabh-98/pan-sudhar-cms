<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BankDocsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        $id = $this->route('id');

        return [

            'service_code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('bank_docs', 'service_code')
                    ->ignore($id),
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'pdf' => [
                $id ? 'nullable' : 'required',
                'file',
                'mimes:pdf',
                'max:5120', // 5 MB
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

        ];
    }

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [

            'service_code.required' => 'Please select a service.',

            'service_code.unique' => 'Guideline already exists for this service.',

            'title.required' => 'Title is required.',

            'pdf.required' => 'Please upload the guideline PDF.',

            'pdf.mimes' => 'Only PDF files are allowed.',

            'pdf.max' => 'PDF size must not exceed 5 MB.',

        ];
    }

    /**
     * Prepare Data Before Validation
     */
    protected function prepareForValidation(): void
    {
        $this->merge([

            'is_active' => $this->boolean('is_active'),

        ]);
    }
}
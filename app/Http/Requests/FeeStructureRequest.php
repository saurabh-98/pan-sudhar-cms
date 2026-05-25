<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeeStructureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'class_id' => 'required|integer|exists:classes,id',

            'fee_type' => 'required|string|max:255',

            'amount' => 'required|numeric|min:0',

            'academic_year' => 'required|string|max:20',

            // ✅ NEW FIELDS
            'is_mandatory' => 'nullable|boolean',

            'fee_category' => 'required|in:one_time,recurring',

            'frequency' => 'required|in:one_time,monthly,yearly',
        ];
    }

    public function messages(): array
    {
        return [

            'class_id.required' => 'Class is required',

            'fee_type.required' => 'Fee type is required',

            'amount.required' => 'Amount is required',
            'amount.numeric' => 'Amount must be numeric',

            'fee_category.required' => 'Select fee category',
            'fee_category.in' => 'Invalid fee category',

            'frequency.required' => 'Select frequency',
            'frequency.in' => 'Invalid frequency',
        ];
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            /* ================= EMPLOYEE ================= */
            'employee_id' => [
                'required',
                'exists:employees,id'
            ],

            /* ================= LEAVE ================= */
            'type' => [
                'required',
                'string',
                'max:100'
            ],

            'from_date' => [
                'required',
                'date'
            ],

            'to_date' => [
                'required',
                'date',
                'after_or_equal:from_date'
            ],

            'leave_duration' => [
                'required',
                'in:Full Day,Half Day'
            ],

            /* ================= DETAILS ================= */
            'reason' => [
                'nullable',
                'string',
                'max:1000'
            ],

            /* ================= DOCUMENT ================= */
            'document' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048'
            ],

            /* ================= EXTRA ================= */
            'is_paid' => [
                'nullable',
                'boolean'
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            'employee_id.required' => 'Employee is required.',
            'employee_id.exists' => 'Selected employee does not exist.',

            'type.required' => 'Leave type is required.',

            'from_date.required' => 'From date is required.',

            'to_date.required' => 'To date is required.',
            'to_date.after_or_equal' => 'To date must be after or equal to from date.',

            'leave_duration.required' => 'Leave duration is required.',

            'document.mimes' => 'Only PDF, JPG, JPEG and PNG files are allowed.',

            'document.max' => 'Document size must not exceed 2MB.',
        ];
    }
}
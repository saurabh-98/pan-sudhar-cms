<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /* ================= AUTHORIZE ================= */
    public function authorize(): bool
    {
        return true; // ✅ MUST BE TRUE
    }

    /* ================= RULES ================= */
    public function rules(): array
    {
        $id = $this->route('id'); // for update

        return [
            'name' => 'required|string|max:255',

            'department_id' => 'required|exists:departments,id',

            'designation_id' => 'required|exists:designations,id',

            'basic_salary' => 'required|numeric|min:0',
        ];
    }

    /* ================= MESSAGES ================= */
    public function messages(): array
    {
        return [
            'name.required' => 'Employee name is required',

            'department_id.required' => 'Department is required',
            'department_id.exists' => 'Invalid department selected',

            'designation_id.required' => 'Designation is required',
            'designation_id.exists' => 'Invalid designation selected',

            'basic_salary.required' => 'Salary is required',
            'basic_salary.numeric' => 'Salary must be a number',
        ];
    }
}
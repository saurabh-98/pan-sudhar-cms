<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificateRequest extends FormRequest
{
    /*
    |--------------------------------------------------------------------------
    | AUTHORIZE
    |--------------------------------------------------------------------------
    */

    public function authorize(): bool
    {
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | RULES
    |--------------------------------------------------------------------------
    */

    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | RELATIONS
            |--------------------------------------------------------------------------
            */

            'student_id' => [

                'nullable',

                'exists:admissions,id'
            ],

            'employee_id' => [

                'nullable',

                'exists:employees,id'
            ],

            /*
            |--------------------------------------------------------------------------
            | CERTIFICATE
            |--------------------------------------------------------------------------
            */

            'certificate_no' => [

                'required',

                'string',

                'max:255',

                'unique:certificates,certificate_no'
            ],

            'certificate_type' => [

                'required',

                'string',

                'max:255'
            ],

            'template_id' => [

                'required',

                'exists:certificate_templates,id'
            ],

            'issue_date' => [

                'required',

                'date'
            ],

            'remarks' => [

                'nullable',

                'string'
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | MESSAGES
    |--------------------------------------------------------------------------
    */

    public function messages(): array
    {
        return [

            'student_id.exists' =>

                'Selected student does not exist.',

            'employee_id.exists' =>

                'Selected employee does not exist.',

            'template_id.exists' =>

                'Selected template does not exist.',

            'certificate_no.unique' =>

                'Certificate number already exists.',
        ];
    }
}
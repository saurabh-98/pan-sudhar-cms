<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
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
        $teacherId = $this->route('id');

        return [

            /*
            |--------------------------------------------------------------------------
            | BASIC INFO
            |--------------------------------------------------------------------------
            */

            'name' => [

                'required',

                'string',

                'max:255'

            ],

            'phone' => [

                'required',

                'string',

                'max:20'

            ],

            'email' => [

                'nullable',

                'email',

                'max:255',

                'unique:teachers,email,' . $teacherId

            ],

            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE
            |--------------------------------------------------------------------------
            */

            'employee_id' => [

                'nullable',

                'string',

                'max:100',

                'unique:teachers,employee_id,' . $teacherId

            ],

            /*
            |--------------------------------------------------------------------------
            | ACADEMIC
            |--------------------------------------------------------------------------
            */

            'qualification' => [

                'nullable',

                'string',

                'max:255'

            ],

            'experience' => [

                'nullable',

                'string',

                'max:255'

            ],

            'specialization' => [

                'nullable',

                'string',

                'max:255'

            ],

            /*
            |--------------------------------------------------------------------------
            | ADDRESS
            |--------------------------------------------------------------------------
            */

            'address' => [

                'nullable',

                'string'

            ],

            /*
            |--------------------------------------------------------------------------
            | PHOTO
            |--------------------------------------------------------------------------
            */

            'photo' => [

                'nullable',

                'image',

                'mimes:jpg,jpeg,png,webp',

                'max:2048'

            ],

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            'status' => [

                'required',

                'in:active,inactive'

            ],

            /*
            |--------------------------------------------------------------------------
            | SUBJECTS
            |--------------------------------------------------------------------------
            */

            'subjects' => [

                'nullable',

                'array'

            ],

            'subjects.*' => [

                'exists:subjects,id'

            ],

            /*
            |--------------------------------------------------------------------------
            | CLASSES
            |--------------------------------------------------------------------------
            */

            'classes' => [

                'nullable',

                'array'

            ],

            'classes.*' => [

                'exists:classes,id'

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

            'name.required' =>

                'Teacher name is required',

            'phone.required' =>

                'Phone number is required',

            'email.email' =>

                'Enter valid email address',

            'email.unique' =>

                'Email already exists',

            'employee_id.unique' =>

                'Employee ID already exists',

            'photo.image' =>

                'Uploaded file must be an image',

            'photo.mimes' =>

                'Only JPG, JPEG, PNG, WEBP allowed',

            'photo.max' =>

                'Image size must be below 2MB',

            'status.required' =>

                'Status is required',

        ];
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            /* STUDENT */
            'name' => ['required','string','max:255'],
            'dob' => ['required','date'],
            'class_id' => ['required','exists:classes,id'],
            'gender' => ['required','in:Male,Female,Other'],

            'blood_group' => ['nullable','in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'aadhaar' => ['nullable','digits:12'],

            'religion' => ['required','string','max:100'],
            'category' => ['required','string','max:100'],

            'state_id' => ['required','exists:states,id'],
            'district_id' => ['required','exists:districts,id'],

            /* PARENTS */
            'father_name' => ['required','string','max:255'],
            'mother_name' => ['required','string','max:255'],

            'father_mobile' => ['nullable','digits:10'],
            'mother_mobile' => ['nullable','digits:10'],

            /* ADDRESS */
            'pincode' => ['required','digits:6'],
            'permanent_address' => ['required','string'],
            'current_address' => ['nullable','string'],

            /* FILES (OPTIONAL IN UPDATE) */
            'photo' => ['nullable','image','mimes:jpg,jpeg,png','max:2048'],
            'birth_certificate' => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:2048'],
            'aadhaar_doc' => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:2048'],
        ];
    }
}
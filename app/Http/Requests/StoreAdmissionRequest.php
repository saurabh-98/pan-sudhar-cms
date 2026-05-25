<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreAdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            /* ================= USER ================= */
            'user_type' => ['required', 'in:parent,student'],

            /* ================= STUDENT ================= */
            'name' => ['required', 'regex:/^[A-Za-z ]+$/', 'max:255'],
            'dob' => ['required', 'date', 'before:today'],
            'class_id' => ['required', 'exists:classes,id'],
            'gender' => ['required', 'in:Male,Female,Other'],
            'blood_group' => ['nullable', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'religion' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:100'],

            'state_id' => ['required', 'integer', 'exists:states,id'],

            'district_id' => [
                'required',
                'integer',
                Rule::exists('districts', 'id')->where(function ($q) {
                    $q->where('state_id', $this->state_id);
                })
            ],

            'aadhaar' => ['nullable', 'digits:12', 'not_in:000000000000'],

            /* ================= PARENTS ================= */
            'father_name' => ['required', 'regex:/^[A-Za-z ]+$/', 'max:255'],
            'mother_name' => ['required', 'regex:/^[A-Za-z ]+$/', 'max:255'],

            'father_aadhaar' => ['required', 'digits:12', 'not_in:000000000000'],
            'mother_aadhaar' => ['required', 'digits:12', 'not_in:000000000000'],

            'father_education' => ['required', 'string', 'max:255'],
            'mother_education' => ['required', 'string', 'max:255'],

            'father_occupation' => ['required', 'string', 'max:255'],
            'mother_occupation' => ['required', 'string', 'max:255'],

            /* CONTACT */
            'father_mobile' => ['nullable', 'digits:10'],
            'mother_mobile' => ['nullable', 'digits:10'],

            'father_email' => ['nullable', 'email', 'max:255'],
            'mother_email' => ['nullable', 'email', 'max:255'],

            'emergency_contact' => ['required', 'digits:10'],

            /* ================= ADDRESS ================= */
            'pincode' => ['required', 'digits:6'],
            'permanent_address' => ['required', 'string', 'max:500'],
            'current_address' => ['nullable', 'string', 'max:500'],

            /* ================= FILES ================= */
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

            'birth_certificate' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048'
            ],

            'aadhaar_doc' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'family_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'father_aadhaar_doc' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'mother_aadhaar_doc' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],

            /* ================= GOOGLE reCAPTCHA ================= */
            'g-recaptcha-response' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [

            'user_type.required' => 'Please select user type',

            'name.required' => 'Student name is required',
            'name.regex' => 'Only alphabets allowed',

            'dob.required' => 'Date of birth is required',
            'dob.before' => 'DOB must be in the past',

            'class_id.required' => 'Select class',

            'gender.required' => 'Select gender',

            'blood_group.in' => 'Invalid blood group selected',
            'religion.required' => 'Religion required',
            'category.required' => 'Category required',

            'state_id.required' => 'State is required',
            'state_id.exists' => 'Invalid state selected',

            'district_id.required' => 'District is required',
            'district_id.exists' => 'Invalid district for selected state',

            'father_name.required' => 'Father name required',
            'mother_name.required' => 'Mother name required',

            'father_aadhaar.required' => 'Father Aadhaar required',
            'mother_aadhaar.required' => 'Mother Aadhaar required',

            'father_mobile.digits' => 'Father mobile must be 10 digits',
            'mother_mobile.digits' => 'Mother mobile must be 10 digits',

            'emergency_contact.required' => 'Emergency contact required',

            'pincode.required' => 'Pincode required',

            'birth_certificate.required' => 'Birth certificate required',

            /* ✅ RECAPTCHA MESSAGE */
            'g-recaptcha-response.required' => 'Please verify captcha',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (empty($this->father_mobile) && empty($this->mother_mobile)) {
                $validator->errors()->add('father_mobile', 'At least one parent mobile required');
                $validator->errors()->add('mother_mobile', 'At least one parent mobile required');
            }

            if ($this->father_aadhaar && $this->mother_aadhaar) {
                if ($this->father_aadhaar === $this->mother_aadhaar) {
                    $validator->errors()->add('father_aadhaar', 'Father & Mother Aadhaar cannot be same');
                }
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
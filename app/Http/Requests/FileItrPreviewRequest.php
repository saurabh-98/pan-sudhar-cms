<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileItrPreviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'aadhaar_front' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'
            ],

            'aadhaar_back' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'
            ],

            'pan_card' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'
            ],

            'existing_files.aadhaar_front' => [
                'nullable',
                'string'
            ],

            'existing_files.aadhaar_back' => [
                'nullable',
                'string'
            ],

            'existing_files.pan_card' => [
                'nullable',
                'string'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'mobile' => [
                'required',
                'digits:10'
            ],

            'email' => [
                'required',
                'email',
                'max:255'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (
                !$this->hasFile('aadhaar_front') &&
                !$this->input('existing_files.aadhaar_front')
            ) {
                $validator->errors()->add(
                    'aadhaar_front',
                    'Aadhaar Front is required.'
                );
            }

            if (
                !$this->hasFile('aadhaar_back') &&
                !$this->input('existing_files.aadhaar_back')
            ) {
                $validator->errors()->add(
                    'aadhaar_back',
                    'Aadhaar Back is required.'
                );
            }

            if (
                !$this->hasFile('pan_card') &&
                !$this->input('existing_files.pan_card')
            ) {
                $validator->errors()->add(
                    'pan_card',
                    'PAN Card is required.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [

            'aadhaar_front.mimes' =>
                'Aadhaar Front must be JPG, PNG or PDF.',

            'aadhaar_front.max' =>
                'Aadhaar Front must not exceed 5 MB.',

            'aadhaar_back.mimes' =>
                'Aadhaar Back must be JPG, PNG or PDF.',

            'aadhaar_back.max' =>
                'Aadhaar Back must not exceed 5 MB.',

            'pan_card.mimes' =>
                'PAN Card must be JPG, PNG or PDF.',

            'pan_card.max' =>
                'PAN Card must not exceed 5 MB.',

            'mobile.digits' =>
                'Mobile number must be exactly 10 digits.',
        ];
    }
}
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
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'
            ],

            'aadhaar_back' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'
            ],

            'pan_card' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120'
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
                'email'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

        ];
    }
}
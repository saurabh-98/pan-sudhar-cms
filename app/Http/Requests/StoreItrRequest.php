<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreItrRequest extends FormRequest
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
                'digits:10',
                'regex:/^[6-9][0-9]{9}$/'
            ],

            'email' => [
                'required',
                'email'
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ];
    }
}
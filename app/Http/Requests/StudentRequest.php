<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'phone' => 'required',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
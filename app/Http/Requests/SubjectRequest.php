<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectRequest extends FormRequest
{
    public function rules()
    {
        return [
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'name' => 'required|string|max:100',
            'status' => 'required|boolean'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
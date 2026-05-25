<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'phone' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
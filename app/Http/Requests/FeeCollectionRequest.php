<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeeCollectionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
        ];
    }
}
<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomeworkRequest extends FormRequest
{
    public function rules()
    {
        return [
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'assigned_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:assigned_date'
        ];
    }
}
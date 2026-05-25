<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherAttendanceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'teacher_id' => 'required|exists:teachers,id',
            'date'       => 'required|date',
            'status'     => 'required|in:Present,Absent,Leave'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
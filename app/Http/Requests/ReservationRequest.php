<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'   => ['required','string','max:255'],
            'email'  => ['required','email'],
            'phone'  => ['required','digits_between:10,15'],

            'date'   => ['required','date','after_or_equal:today'],
            'time'   => ['required'],

            'guests' => ['required','integer','min:1','max:20'],

            'notes'  => ['nullable','string'],
            'status' => ['nullable','in:pending,confirmed,cancelled']
        ];
    }
}
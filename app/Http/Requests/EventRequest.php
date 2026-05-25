<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Authorize
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rules
     */
    public function rules(): array
    {
        return [

            'title' => 'required|string|max:255',

            'description' => 'nullable|string',

            'event_date' => 'required|date',

            'start_time' => 'nullable',

            'end_time' => 'nullable',

            'location' => 'nullable|string|max:255',

            'category_id' => 'nullable|integer',

            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'is_holiday' => 'nullable|boolean',

            'status' => 'required|in:Upcoming,Ongoing,Completed,Cancelled',
        ];
    }
}
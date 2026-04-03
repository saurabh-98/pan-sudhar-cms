<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // you can add role check later
    }

    public function rules(): array
    {
        $id = $this->route('id'); // for update case

        return [
            'title'   => 'required|string|max:255',
            'slug'    => 'required|string|max:255|unique:pages,slug,' . $id,
            'content' => 'nullable|string',
            'status'  => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Page title is required',
            'slug.required'  => 'Slug is required',
            'slug.unique'    => 'Slug already exists',
        ];
    }
}
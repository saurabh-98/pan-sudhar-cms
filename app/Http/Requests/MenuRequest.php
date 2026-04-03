<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        
        if ($this->isMethod('post') && !$this->route('id')) {
            return [
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'image' => 'required|image|mimes:jpg,jpeg,png,webp',
                'category_id' => 'required|exists:categories,id',
                'description' => 'nullable|string',
                'specifications' => 'nullable|string',
            ];
        }


        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
        ];
    }

   
    public function messages()
    {
        return [
            'name.required' => 'Menu name is required',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'image.required' => 'Menu image is required',
            'image.image' => 'File must be an image',
            'category_id.required' => 'Please select a category',
            'description.string' => 'Description must be text',
            'specifications.string' => 'Specifications must be text',
        ];
    }
}
<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|in:photo,video',
            'file' => $this->type === 'photo'
                ? 'required|image|mimes:jpg,jpeg,png|max:2048'
                : 'required|url'
        ];
    }
}
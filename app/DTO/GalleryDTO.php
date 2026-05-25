<?php
namespace App\DTO;

use Illuminate\Http\Request;

class GalleryDTO
{
    public function __construct(
        public string $title,
        public string $type,
        public $file
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            title: $request->title,
            type: $request->type,
            file: $request->type === 'photo'
                ? $request->file('file')
                : $request->input('file')
        );
    }
}
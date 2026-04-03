<?php

namespace App\DTO;

use Illuminate\Support\Str;

class NewsDTO
{
    public function __construct(
        public string $title,
        public ?string $description,
        public ?string $image,
        public ?string $slug = null,
        public bool $is_active = true
    ) {}

    /* =========================
       CREATE FROM REQUEST
    ========================= */
    public static function fromRequest($request, $imagePath = null): self
    {
        return new self(
            title: $request->title,
            description: $request->description,
            image: $imagePath,
            slug: Str::slug($request->title),
            is_active: $request->has('is_active') ? true : true // default active
        );
    }

    /* =========================
       CONVERT TO ARRAY
    ========================= */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug ?? Str::slug($this->title),
            'description' => $this->description,
            'image' => $this->image,
            'is_active' => $this->is_active,
        ];
    }
}
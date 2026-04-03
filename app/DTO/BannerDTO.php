<?php

namespace App\DTO;

class BannerDTO
{
    public function __construct(
        public ?string $title = null,
        public ?string $subtitle = null,
        public ?string $button_text = null,
        public ?array $image = null, // ✅ always array
        public string $type = 'hero'
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            title: $request->input('title'),
            subtitle: $request->input('subtitle'),
            button_text: $request->input('button_text'),
            image: null, // ✅ service will handle
            type: $request->input('type', 'hero') // ✅ dynamic (future-proof)
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'button_text' => $this->button_text,
            'image' => $this->image ?? [], // ✅ never null (safe)
            'type' => $this->type,
        ];
    }
}
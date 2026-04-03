<?php

namespace App\DTO;

use Illuminate\Http\Request;

class PopupOfferDTO
{
    public function __construct(
        public string $title,
        public ?string $description,
        public ?string $image,
        public int $is_active,
        public ?string $start_at,
        public ?string $end_at
    ) {}

    public static function fromRequest(Request $request, $imagePath = null): self
    {
        return new self(
            title: $request->title,
            description: $request->description,
            image: $imagePath,
            is_active: $request->is_active ?? 1,
            start_at: $request->start_at,
            end_at: $request->end_at
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'is_active' => $this->is_active,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
        ];
    }
}
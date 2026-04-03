<?php

namespace App\DTO;

class FeatureDTO
{
    public function __construct(
        public ?string $icon,
        public string $title,
        public ?string $description,
        public bool $is_active = true
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            icon: $request->icon,
            title: $request->title,
            description: $request->description,
            is_active: $request->has('is_active') ? true : false
        );
    }

    public function toArray(): array
    {
        return [
            'icon' => $this->icon,
            'title' => $this->title,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];
    }
}
<?php
namespace App\DTO;

class CampaignDTO
{
    public function __construct(
        public ?string $tag,
        public string $title,
        public ?string $description,
        public ?float $price,
        public ?string $image,
        public bool $is_active = true
    ) {}

    public static function fromRequest($request, $imagePath = null): self
    {
        return new self(
            tag: $request->tag,
            title: $request->title,
            description: $request->description,
            price: $request->price,
            image: $imagePath,
            is_active: true
        );
    }

    public function toArray(): array
    {
        return [
            'tag' => $this->tag,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'image' => $this->image,
            'is_active' => $this->is_active,
        ];
    }
}
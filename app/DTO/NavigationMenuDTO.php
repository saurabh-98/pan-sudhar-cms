<?php
namespace App\DTO;

class NavigationMenuDTO
{
    public function __construct(
        public string $name,
        public string $url,
        public ?int $order,
        public ?bool $status
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            $request->name,
            $request->url,
            $request->order ?? 0,
            $request->status ?? 1
        );
    }

    public function toArray(): array
    {
        return [
            'name'   => $this->name,
            'url'    => $this->url,
            'order'  => $this->order,
            'status' => $this->status,
        ];
    }
}
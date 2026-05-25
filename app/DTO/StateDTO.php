<?php

namespace App\DTO;

class StateDTO
{
    public function __construct(
        public string $name,
        public bool $status = true
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            name: trim($request->name),
            status: true
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'status' => $this->status
        ];
    }
}
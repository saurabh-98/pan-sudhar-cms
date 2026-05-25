<?php

namespace App\DTO;

class DistrictDTO
{
    public function __construct(
        public int $state_id,
        public string $name,
        public bool $status = true
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            state_id: (int) $request->state_id,
            name: trim($request->name),
            status: true
        );
    }

    public function toArray(): array
    {
        return [
            'state_id' => $this->state_id,
            'name' => $this->name,
            'status' => $this->status
        ];
    }
}
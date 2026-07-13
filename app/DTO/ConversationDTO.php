<?php

namespace App\DTO;

class ConversationDTO
{
    public function __construct(
        public readonly int $retailer_id,
        public readonly ?int $admin_id = null,
        public readonly string $status = 'waiting',
    ) {}

    /**
     * Create DTO
     */
    public static function fromArray(array $data): self
    {
        return new self(
            retailer_id: $data['retailer_id'],
            admin_id: $data['admin_id'] ?? null,
            status: $data['status'] ?? 'waiting',
        );
    }

    /**
     * Convert DTO to array
     */
    public function toArray(): array
    {
        return [
            'retailer_id' => $this->retailer_id,
            'admin_id'    => $this->admin_id,
            'status'      => $this->status,
        ];
    }
}
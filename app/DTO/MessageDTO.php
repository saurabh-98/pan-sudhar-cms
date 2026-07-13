<?php

namespace App\DTO;

class MessageDTO
{
    public function __construct(
        public readonly int $conversation_id,
        public readonly int $sender_id,
        public readonly string $sender_type,
        public readonly ?string $message = null,
        public readonly ?string $attachment = null,
        public readonly ?string $attachment_name = null,
        public readonly ?string $attachment_type = null,
    ) {}

    /**
     * Create DTO
     */
    public static function fromArray(array $data): self
    {
        return new self(
            conversation_id: $data['conversation_id'],
            sender_id: $data['sender_id'],
            sender_type: $data['sender_type'],
            message: $data['message'] ?? null,
            attachment: $data['attachment'] ?? null,
            attachment_name: $data['attachment_name'] ?? null,
            attachment_type: $data['attachment_type'] ?? null,
        );
    }

    /**
     * Convert DTO to array
     */
    public function toArray(): array
    {
        return [
            'conversation_id' => $this->conversation_id,
            'sender_id'       => $this->sender_id,
            'sender_type'     => $this->sender_type,
            'message'         => $this->message,
            'attachment'      => $this->attachment,
            'attachment_name' => $this->attachment_name,
            'attachment_type' => $this->attachment_type,
        ];
    }
}
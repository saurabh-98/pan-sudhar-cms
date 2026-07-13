<?php

namespace App\DTO;

use Illuminate\Http\Request;

class ChatDTO
{
    public function __construct(
        public ?int $conversation_id = null,
        public ?string $conversation_uuid = null,

        public ?int $retailer_id = null,
        public ?int $admin_id = null,

        public ?string $sender_type = null,
        public ?int $sender_id = null,

        public ?string $message = null,

        public ?string $attachment = null,
        public ?string $attachment_name = null,
        public ?string $attachment_type = null,
    ) {}

    /**
     * Create Conversation DTO
     */
    public static function createConversation(
        int $retailerId,
        ?int $adminId = null
    ): self {

        return new self(
            retailer_id: $retailerId,
            admin_id: $adminId
        );
    }

    /**
     * Send Message DTO
     */
    public static function sendMessage(
        Request $request,
        int $conversationId,
        string $senderType,
        int $senderId,
        ?string $attachment = null
    ): self {

        return new self(
            conversation_id: $conversationId,
            sender_type: $senderType,
            sender_id: $senderId,

            message: $request->message,

            attachment: $attachment,

            attachment_name: $request->file('attachment')
                ? $request->file('attachment')->getClientOriginalName()
                : null,

            attachment_type: $request->file('attachment')
                ? $request->file('attachment')->getMimeType()
                : null
        );
    }

    /**
     * Assign Admin DTO
     */
    public static function assignAdmin(
        int $conversationId,
        int $adminId
    ): self {

        return new self(
            conversation_id: $conversationId,
            admin_id: $adminId
        );
    }

    /**
     * Find Conversation DTO
     */
    public static function find(
        int $conversationId
    ): self {

        return new self(
            conversation_id: $conversationId
        );
    }

    /**
     * Search DTO
     */
    public static function search(
        string $keyword
    ): self {

        return new self(
            message: $keyword
        );
    }
}
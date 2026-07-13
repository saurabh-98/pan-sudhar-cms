<?php

namespace App\Services;

use App\DTO\MessageDTO;
use App\Events\MessageRead;
use App\Events\MessageSent;
use App\Repositories\MessageRepository;

class MessageService
{
    public function __construct(
        protected MessageRepository $messageRepository
    ) {}

    /**
     * Send Message
     */
    public function send(MessageDTO $dto)
    {
        $message = $this->messageRepository->send(
            $dto->toArray()
        );

        /*
        |--------------------------------------------------------------------------
        | Broadcast New Message
        |--------------------------------------------------------------------------
        */

        broadcast(
            new MessageSent($message)
        )->toOthers();

        return $message;
    }

    /**
     * Get Messages
     */
    public function messages(int $conversationId)
    {
        return $this->messageRepository
            ->messages($conversationId);
    }

    /**
     * Mark As Read
     */
    public function markAsRead(
        int $conversationId,
        string $receiverType
    ): void {

        $this->messageRepository
            ->markAsRead(
                $conversationId,
                $receiverType
            );

        /*
        |--------------------------------------------------------------------------
        | Broadcast Read Status
        |--------------------------------------------------------------------------
        */

        broadcast(
            new MessageRead(
                $conversationId,
                $receiverType
            )
        )->toOthers();

    }

    /**
     * Delete Message
     */
    public function delete(int $messageId): bool
    {
        return $this->messageRepository
            ->delete($messageId);
    }

    /**
     * Retailer Unread Count
     */
    public function retailerUnreadCount(
        int $retailerId
    ): int {

        return $this->messageRepository
            ->retailerUnreadCount($retailerId);
    }

    /**
     * Admin Unread Count
     */
    public function adminUnreadCount(): int
    {
        return $this->messageRepository
            ->adminUnreadCount();
    }
}
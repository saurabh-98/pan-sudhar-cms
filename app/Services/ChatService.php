<?php

namespace App\Services;

use App\DTO\ChatDTO;
use App\Repositories\ChatRepository;
use Illuminate\Support\Str;

class ChatService
{
    public function __construct(
        protected ChatRepository $repository
    ) {}

    /**
     * Create Conversation
     */
    public function createConversation(ChatDTO $dto)
    {
        return $this->repository->createConversation([
            'conversation_id' => (string) Str::uuid(),
            'retailer_id'     => $dto->retailer_id,
            'admin_id'        => $dto->admin_id,
            'status'          => 'waiting',
            'last_message'    => null,
            'last_message_at' => now(),
        ]);
    }

    /**
     * Get Conversation
     */
    public function conversation(int $id)
    {
        return $this->repository->findById($id);
    }

    /**
     * Get By UUID
     */
    public function conversationByUuid(string $uuid)
    {
        return $this->repository->findByConversationId($uuid);
    }

    /**
     * Retailer Conversations
     */
    public function retailerChats(int $retailerId)
    {
        return $this->repository->retailerConversations($retailerId);
    }

    /**
     * Admin Conversations
     */
    public function adminChats()
    {
        return $this->repository->adminConversations();
    }

    /**
     * Conversation Messages
     */
    public function messages(int $conversationId)
    {
        return $this->repository->messages($conversationId);
    }

    /**
     * Send Message
     */
    public function send(ChatDTO $dto)
    {
        $message = $this->repository->sendMessage([
            'conversation_id' => $dto->conversation_id,
            'sender_type'     => $dto->sender_type,
            'sender_id'       => $dto->sender_id,
            'message'         => $dto->message,
            'attachment'      => $dto->attachment,
            'attachment_name' => $dto->attachment_name,
            'attachment_type' => $dto->attachment_type,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Broadcast Event
        |--------------------------------------------------------------------------
        |
        | Uncomment after creating Reverb Event
        |
        */

        // broadcast(new MessageSent($message))->toOthers();

        return $message;
    }

    /**
     * Mark Read
     */
    public function markRead(
        int $conversationId,
        string $receiverType
    ): void {

        $this->repository->markAsRead(
            $conversationId,
            $receiverType
        );
    }

    /**
     * Assign Admin
     */
    public function assignAdmin(
        int $conversationId,
        int $adminId
    ): bool {

        return $this->repository->assignAdmin(
            $conversationId,
            $adminId
        );
    }

    /**
     * Close Chat
     */
    public function close(int $conversationId): bool
    {
        return $this->repository->closeConversation(
            $conversationId
        );
    }

    /**
     * Reopen Chat
     */
    public function reopen(int $conversationId): bool
    {
        return $this->repository->openConversation(
            $conversationId
        );
    }

    /**
     * Delete Conversation
     */
    public function delete(int $conversationId): bool
    {
        return $this->repository->deleteConversation(
            $conversationId
        );
    }

    /**
     * Retailer Unread Messages
     */
    public function retailerUnreadCount(
        int $retailerId
    ): int {

        return $this->repository->retailerUnreadCount(
            $retailerId
        );
    }

    /**
     * Admin Unread Messages
     */
    public function adminUnreadCount(): int
    {
        return $this->repository->adminUnreadCount();
    }

    /**
     * Search Conversations
     */
    public function search(string $keyword)
    {
        return $this->repository->search($keyword);
    }

    /**
     * Create Conversation If Not Exists
     */
    public function createIfNotExists(ChatDTO $dto)
    {
        $conversation = $this->repository
            ->latestConversation($dto->retailer_id);

        if (
            $conversation &&
            $conversation->status != 'closed'
        ) {
            return $conversation;
        }

        return $this->createConversation($dto);
    }
}
<?php

namespace App\Services;

use App\DTO\ConversationDTO;
use App\Events\ConversationAssigned;
use App\Repositories\ConversationRepository;
use Illuminate\Support\Str;

class ConversationService
{
    public function __construct(
        protected ConversationRepository $conversationRepository
    ) {}

    /**
     * Create Conversation
     */
    public function create(ConversationDTO $dto)
    {
        return $this->conversationRepository->create([
            'conversation_id' => (string) Str::uuid(),
            'retailer_id'     => $dto->retailer_id,
            'admin_id'        => $dto->admin_id,
            'status'          => $dto->status,
            'last_message'    => null,
            'last_message_at' => now(),
        ]);
    }

    /**
     * Create Conversation If No Active Conversation Exists
     */
    public function createIfNotExists(ConversationDTO $dto)
    {
        $conversation = $this->conversationRepository
            ->latestByRetailer($dto->retailer_id);

        if (
            $conversation &&
            in_array($conversation->status, ['waiting', 'active'])
        ) {
            return $conversation;
        }

        return $this->create($dto);
    }

    /**
     * Find Conversation
     */
    public function find(int $id)
    {
        return $this->conversationRepository->find($id);
    }

    /**
     * Find By UUID
     */
    public function findByUuid(string $uuid)
    {
        return $this->conversationRepository->findByUuid($uuid);
    }

    /**
     * Retailer Conversations
     */
    public function retailerConversations(int $retailerId)
    {
        return $this->conversationRepository
            ->retailerConversations($retailerId);
    }

    /**
     * Admin Conversations
     */
    public function adminConversations(array $filters = [])
    {
        return $this->conversationRepository
            ->adminConversations($filters);
    }

    /**
     * Assign Admin
     */
    public function assignAdmin(
        int $conversationId,
        int $adminId
    )
    {
        $this->conversationRepository
            ->assignAdmin(
                $conversationId,
                $adminId
            );

        $conversation = $this->conversationRepository
            ->find($conversationId);

        broadcast(
            new ConversationAssigned($conversation)
        )->toOthers();

        return $conversation;
    }

    /**
     * Close Conversation
     */
    public function close(int $conversationId): bool
    {
        return $this->conversationRepository
            ->close($conversationId);
    }

    /**
     * Reopen Conversation
     */
    public function reopen(int $conversationId): bool
    {
        return $this->conversationRepository
            ->reopen($conversationId);
    }

    /**
     * Delete Conversation
     */
    public function delete(int $conversationId): bool
    {
        return $this->conversationRepository
            ->delete($conversationId);
    }

    /**
     * Search
     */
    public function search(string $keyword)
    {
        return $this->conversationRepository
            ->search($keyword);
    }
}
<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class ChatRepository
{
    /**
     * Create new conversation
     */
    public function createConversation(array $data): Conversation
    {
        return Conversation::create($data);
    }

    /**
     * Find conversation by ID
     */
    public function findById(int $id): ?Conversation
    {
        return Conversation::with([
            'retailer',
            'admin'
        ])->find($id);
    }

    /**
     * Find by UUID
     */
    public function findByConversationId(string $conversationId): ?Conversation
    {
        return Conversation::where('conversation_id', $conversationId)
            ->first();
    }

    /**
     * Retailer Conversations
     */
    public function retailerConversations(int $retailerId)
    {
        return Conversation::with([
                'admin'
            ])
            ->where('retailer_id', $retailerId)
            ->latest('last_message_at')
            ->paginate(20);
    }

    /**
     * Admin Conversations
     */
    public function adminConversations()
    {
        return Conversation::with([
                'retailer'
            ])
            ->latest('last_message_at')
            ->paginate(20);
    }

    /**
     * Get Conversation Messages
     */
    public function messages(int $conversationId)
    {
        return Message::with('sender')
            ->where('conversation_id', $conversationId)
            ->orderBy('id')
            ->get();
    }

    /**
     * Send Message
     */
    public function sendMessage(array $data): Message
    {
        return DB::transaction(function () use ($data) {

            $message = Message::create($data);

            Conversation::where('id', $data['conversation_id'])
                ->update([
                    'last_message'    => $data['message'],
                    'last_message_at' => now(),
                ]);

            return $message;
        });
    }

    /**
     * Mark Messages As Read
     */
    public function markAsRead(
        int $conversationId,
        string $receiverType
    ): void {

        $senderType = $receiverType === 'admin'
            ? 'retailer'
            : 'admin';

        Message::where('conversation_id', $conversationId)
            ->where('sender_type', $senderType)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Assign Admin
     */
    public function assignAdmin(
        int $conversationId,
        int $adminId
    ): bool {

        return Conversation::where('id', $conversationId)
            ->update([
                'admin_id' => $adminId,
                'status'   => 'active',
            ]);
    }

    /**
     * Close Conversation
     */
    public function closeConversation(int $conversationId): bool
    {
        return Conversation::where('id', $conversationId)
            ->update([
                'status'    => 'closed',
                'closed_at' => now(),
            ]);
    }

    /**
     * Open Conversation
     */
    public function openConversation(int $conversationId): bool
    {
        return Conversation::where('id', $conversationId)
            ->update([
                'status' => 'active',
            ]);
    }

    /**
     * Delete Conversation
     */
    public function deleteConversation(int $conversationId): bool
    {
        return Conversation::where('id', $conversationId)
            ->delete();
    }

    /**
     * Retailer Unread Count
     */
    public function retailerUnreadCount(int $retailerId): int
    {
        return Message::whereHas('conversation', function ($query) use ($retailerId) {
                $query->where('retailer_id', $retailerId);
            })
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();
    }

    /**
     * Admin Unread Count
     */
    public function adminUnreadCount(): int
    {
        return Message::where('sender_type', 'retailer')
            ->where('is_read', false)
            ->count();
    }

    /**
     * Search Conversation
     */
    public function search(string $keyword)
    {
        return Conversation::whereHas('retailer', function ($query) use ($keyword) {

                $query->where('name', 'LIKE', "%{$keyword}%");

            })
            ->orWhere('conversation_id', 'LIKE', "%{$keyword}%")
            ->paginate(20);
    }

    /**
     * Latest Conversation
     */
    public function latestConversation(int $retailerId): ?Conversation
    {
        return Conversation::where('retailer_id', $retailerId)
            ->latest()
            ->first();
    }
}
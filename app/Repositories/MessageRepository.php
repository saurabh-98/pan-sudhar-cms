<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MessageRepository
{
    public function create(array $data): Message
    {
        return DB::transaction(function () use ($data) {

            $message = Message::create($data);

            Conversation::whereKey($data['conversation_id'])
                ->update([
                    'last_message'    => $data['message'],
                    'last_message_at' => now(),
                ]);

            return $message;
        });
    }

    public function find(int $id): ?Message
    {
        return Message::with('sender')->find($id);
    }

    public function conversationMessages(
        int $conversationId,
        int $perPage = 50
    ): LengthAwarePaginator {

        return Message::with('sender')

            ->where('conversation_id', $conversationId)

            ->oldest()

            ->paginate($perPage);
    }

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

    public function delete(int $id): bool
    {
        return Message::whereKey($id)
                ->delete() > 0;
    }

    public function retailerUnreadCount(
        int $retailerId
    ): int {

        return Message::whereHas(
                'conversation',
                fn($q) => $q->where(
                    'retailer_id',
                    $retailerId
                )
            )

            ->where('sender_type', 'admin')

            ->where('is_read', false)

            ->count();
    }

    public function adminUnreadCount(): int
    {
        return Message::where('sender_type', 'retailer')

            ->where('is_read', false)

            ->count();
    }
}
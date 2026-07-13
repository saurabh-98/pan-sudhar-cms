<?php

namespace App\Repositories;

use App\Models\Conversation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ConversationRepository
{
    public function create(array $data): Conversation
    {
        return Conversation::create($data);
    }

    public function find(int $id): ?Conversation
    {
        return Conversation::with([
            'retailer',
            'admin',
            'messages.sender',
        ])->find($id);
    }

    public function findByUuid(string $uuid): ?Conversation
    {
        return Conversation::with([
            'retailer',
            'admin',
            'messages.sender',
        ])
            ->where('conversation_id', $uuid)
            ->first();
    }

    public function latestByRetailer(int $retailerId): ?Conversation
    {
        return Conversation::where('retailer_id', $retailerId)
            ->latest('id')
            ->first();
    }

    public function retailerConversations(
        int $retailerId,
        int $perPage = 20
    ): LengthAwarePaginator {
        return Conversation::with('admin')
            ->where('retailer_id', $retailerId)
            ->latest('last_message_at')
            ->paginate($perPage);
    }

    public function adminConversations(
        array $filters = [],
        int $perPage = 20
    ): LengthAwarePaginator {

        return Conversation::query()

            ->with([
                'retailer',
                'admin',
            ])

            ->when(
                $filters['status'] ?? null,
                fn($q, $status) => $q->where('status', $status)
            )

            ->when(
                $filters['assigned_to'] ?? null,
                fn($q, $adminId) => $q->where('admin_id', $adminId)
            )

            ->when(
                $filters['search'] ?? null,
                function ($q, $search) {

                    $q->where(function ($query) use ($search) {

                        $query
                            ->where(
                                'conversation_id',
                                'LIKE',
                                "%{$search}%"
                            )

                            ->orWhereHas(
                                'retailer',
                                fn($retailer) => $retailer
                                    ->where(
                                        'name',
                                        'LIKE',
                                        "%{$search}%"
                                    )
                            );
                    });
                }
            )

            ->latest('last_message_at')

            ->paginate($perPage);
    }

    public function assignAdmin(
        int $conversationId,
        int $adminId
    ): bool {

        return Conversation::whereKey($conversationId)
                ->update([
                    'admin_id' => $adminId,
                    'status'   => 'active',
                ]) > 0;
    }

    public function close(int $conversationId): bool
    {
        return Conversation::whereKey($conversationId)
                ->update([
                    'status'    => 'closed',
                    'closed_at' => now(),
                ]) > 0;
    }

    public function reopen(int $conversationId): bool
    {
        return Conversation::whereKey($conversationId)
                ->update([
                    'status'    => 'active',
                    'closed_at' => null,
                ]) > 0;
    }

    public function updateLastMessage(
        int $conversationId,
        ?string $message
    ): bool {

        return Conversation::whereKey($conversationId)
                ->update([
                    'last_message'    => $message,
                    'last_message_at' => now(),
                ]) > 0;
    }

    public function delete(int $conversationId): bool
    {
        return Conversation::whereKey($conversationId)
                ->delete() > 0;
    }
}
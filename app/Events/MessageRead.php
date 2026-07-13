<?php

namespace App\Events;

use App\Models\Conversation;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    /**
     * Conversation
     */
    public Conversation $conversation;

    /**
     * Receiver Type
     */
    public string $receiverType;

    /**
     * Create Event
     */
    public function __construct(
        int $conversationId,
        string $receiverType
    ) {

        $this->conversation = Conversation::findOrFail(
            $conversationId
        );

        $this->receiverType = $receiverType;

    }

    /**
     * Broadcast Channel
     */
    public function broadcastOn(): array
    {
        return [

            new PrivateChannel(
                'conversation.' .
                $this->conversation->conversation_id
            )

        ];
    }

    /**
     * Event Name
     */
    public function broadcastAs(): string
    {
        return 'message.read';
    }

    /**
     * Payload
     */
    public function broadcastWith(): array
    {
        return [

            'conversation_id' => $this->conversation->conversation_id,

            'receiver_type' => $this->receiverType,

            'read_at' => now()->toDateTimeString(),

        ];
    }
}
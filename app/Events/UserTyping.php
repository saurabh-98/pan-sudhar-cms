<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTyping implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    /**
     * Conversation UUID
     */
    public string $conversationId;

    /**
     * User Name
     */
    public string $user;

    /**
     * Sender Type
     */
    public string $senderType;

    /**
     * Create Event
     */
    public function __construct(
        string $conversationId,
        string $user,
        string $senderType
    ) {
        $this->conversationId = $conversationId;
        $this->user = $user;
        $this->senderType = $senderType;
    }

    /**
     * Broadcast Channel
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(
                'conversation.' . $this->conversationId
            ),
        ];
    }

    /**
     * Event Name
     */
    public function broadcastAs(): string
    {
        return 'typing';
    }

    /**
     * Broadcast Payload
     */
    public function broadcastWith(): array
    {
        return [

            'conversation_id' => $this->conversationId,

            'user' => $this->user,

            'sender_type' => $this->senderType,

        ];
    }
}
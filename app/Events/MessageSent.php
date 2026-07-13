<?php

namespace App\Events;

use App\Http\Resources\Chat\MessageResource;
use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    /**
     * The message instance.
     */
    public Message $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message->load('sender', 'conversation');
    }

    /**
     * Broadcast Channel
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(
                'conversation.' .
                $this->message->conversation->conversation_id
            ),
        ];
    }

    /**
     * Event Name
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Broadcast Payload
     */
    public function broadcastWith(): array
    {
        return [
            'message' => (new MessageResource(
                $this->message
            ))->resolve(),
        ];
    }
}
<?php

namespace App\Events;

use App\Http\Resources\Chat\ConversationResource;
use App\Models\Conversation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationAssigned implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    /**
     * Conversation
     */
    public Conversation $conversation;

    /**
     * Create Event
     */
    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation->load([
            'retailer',
            'admin',
        ]);
    }

    /**
     * Broadcast Channel
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('admin.chat'),
        ];
    }

    /**
     * Event Name
     */
    public function broadcastAs(): string
    {
        return 'conversation.assigned';
    }

    /**
     * Payload
     */
    public function broadcastWith(): array
    {
        return [
            'conversation' => (new ConversationResource(
                $this->conversation
            ))->resolve(),
        ];
    }
}
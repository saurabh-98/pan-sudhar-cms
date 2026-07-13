<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'conversation_id' => $this->conversation_id,

            'status' => $this->status,

            'last_message' => $this->last_message,

            'last_message_at' => optional($this->last_message_at)
                ?->toDateTimeString(),

            'closed_at' => optional($this->closed_at)
                ?->toDateTimeString(),

            'retailer' => [

                'id' => $this->retailer?->id,

                'name' => $this->retailer?->name,

                'mobile' => $this->retailer?->mobile,

            ],

            'admin' => $this->admin ? [

                'id' => $this->admin->id,

                'name' => $this->admin->name,

            ] : null,

            'unread_count' => $this->whenCounted(
                'messages',
                fn () => $this->messages_count
            ),

            'created_at' => $this->created_at
                ->toDateTimeString(),

        ];
    }
}
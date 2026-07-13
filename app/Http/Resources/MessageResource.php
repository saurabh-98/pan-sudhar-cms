<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'conversation_id' => $this->conversation_id,

            'sender_id' => $this->sender_id,

            'sender_type' => $this->sender_type,

            'sender_name' => $this->sender?->name,

            'message' => $this->message,

            'attachment' => $this->attachment,

            'attachment_name' => $this->attachment_name,

            'attachment_type' => $this->attachment_type,

            'is_read' => (bool) $this->is_read,

            'read_at' => optional($this->read_at)
                ?->toDateTimeString(),

            'created_at' => $this->created_at
                ->toDateTimeString(),

            'created_at_human' => $this->created_at
                ->diffForHumans(),

        ];
    }
}
<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ConversationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'data' => ConversationResource::collection(
                $this->collection
            ),

        ];
    }

    /**
     * Additional response data.
     */
    public function with(Request $request): array
    {
        return [

            'success' => true,

            'message' => 'Conversations fetched successfully.',

        ];
    }
}
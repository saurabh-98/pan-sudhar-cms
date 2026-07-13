<?php

namespace App\Http\Controllers\Retailer;

use App\DTO\MessageDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\SendMessageRequest;
use App\Http\Resources\Chat\MessageResource;
use App\Services\ChatAttachmentService;
use App\Services\ConversationService;
use App\Services\MessageService;

class MessageController extends Controller
{
    public function __construct(
        protected MessageService $messageService,
        protected ConversationService $conversationService,
        protected ChatAttachmentService $attachmentService
    ) {}

    /**
     * Conversation Messages
     */
    public function index(string $conversation)
    {
        $conversation = $this->conversationService
            ->findByUuid($conversation);

        abort_if(!$conversation, 404);

        abort_if(
            $conversation->retailer_id != auth()->id(),
            403
        );

        $messages = $this->messageService
            ->messages($conversation->id);

        return response()->json([
            'success' => true,
            'message' => 'Messages fetched successfully.',
            'data' => MessageResource::collection($messages),
        ]);
    }

    /**
     * Send Message
     */
    public function store(
        SendMessageRequest $request,
        string $conversation
    ) {

        $conversation = $this->conversationService
            ->findByUuid($conversation);

        abort_if(!$conversation, 404);

        abort_if(
            $conversation->retailer_id != auth()->id(),
            403
        );

        $upload = null;

        if ($request->hasFile('attachment')) {

            $upload = $this->attachmentService
                ->upload(
                    $request->file('attachment')
                );

        }

        $dto = MessageDTO::fromArray([

            'conversation_id' => $conversation->id,

            'sender_id' => auth()->id(),

            'sender_type' => 'retailer',

            'message' => $request->message,

            'attachment' => $upload['path'] ?? null,

            'attachment_name' => $upload['name'] ?? null,

            'attachment_type' => $upload['type'] ?? null,

        ]);

        $message = $this->messageService
            ->send($dto);

        return response()->json([

            'success' => true,

            'message' => 'Message sent successfully.',

            'data' => new MessageResource($message),

        ], 201);

    }

    /**
     * Mark Messages As Read
     */
    public function markAsRead(string $conversation)
    {

        $conversation = $this->conversationService
            ->findByUuid($conversation);

        abort_if(!$conversation, 404);

        abort_if(
            $conversation->retailer_id != auth()->id(),
            403
        );

        $this->messageService
            ->markAsRead(
                $conversation->id,
                'retailer'
            );

        return response()->json([

            'success' => true,

            'message' => 'Messages marked as read.',

        ]);

    }

    /**
     * Delete Message
     */
    public function destroy(int $message)
    {

        $this->messageService
            ->delete($message);

        return response()->json([

            'success' => true,

            'message' => 'Message deleted successfully.',

        ]);

    }
}
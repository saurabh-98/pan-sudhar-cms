<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ConversationService;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function __construct(
        protected ConversationService $conversationService
    ) {}

    /**
     * Conversation Dashboard
     */
    public function index(Request $request)
    {
        $filters = [

            'status' => $request->status,

            'assigned_to' => $request->assigned_to,

            'search' => $request->search,

        ];

        $conversations = $this->conversationService
            ->adminConversations($filters);

        return view(
            'admin.chat.index',
            compact('conversations')
        );
    }

    /**
     * Show Conversation
     */
    public function show(string $conversationId)
    {
        $conversation = $this->conversationService
            ->findByUuid($conversationId);

        abort_if(!$conversation, 404);

        $conversation->load([
            'retailer',
            'admin',
            'messages.sender',
        ]);

        return view(
            'admin.chat.show',
            compact('conversation')
        );
    }

    /**
     * Assign Conversation
     */
    public function assign(Request $request, string $conversationId)
    {
        $conversation = $this->conversationService
            ->findByUuid($conversationId);

        abort_if(!$conversation, 404);

        $this->conversationService->assignAdmin(
            $conversation->id,
            auth()->id()
        );

        if ($request->expectsJson()) {

            return response()->json([
                'success' => true,
                'message' => 'Conversation assigned successfully.',
            ]);

        }

        return back()->with(
            'success',
            'Conversation assigned successfully.'
        );
    }

    /**
     * Close Conversation
     */
    public function close(Request $request, string $conversationId)
    {
        $conversation = $this->conversationService
            ->findByUuid($conversationId);

        abort_if(!$conversation, 404);

        $this->conversationService->close(
            $conversation->id
        );

        if ($request->expectsJson()) {

            return response()->json([
                'success' => true,
                'message' => 'Conversation closed successfully.',
            ]);

        }

        return back()->with(
            'success',
            'Conversation closed successfully.'
        );
    }

    /**
     * Reopen Conversation
     */
    public function reopen(Request $request, string $conversationId)
    {
        $conversation = $this->conversationService
            ->findByUuid($conversationId);

        abort_if(!$conversation, 404);

        $this->conversationService->reopen(
            $conversation->id
        );

        if ($request->expectsJson()) {

            return response()->json([
                'success' => true,
                'message' => 'Conversation reopened successfully.',
            ]);

        }

        return back()->with(
            'success',
            'Conversation reopened successfully.'
        );
    }
}
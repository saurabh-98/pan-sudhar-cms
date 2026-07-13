<?php

namespace App\Http\Controllers\Retailer;

use App\DTO\ConversationDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\StoreConversationRequest;
use App\Services\ConversationService;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function __construct(
        protected ConversationService $conversationService
    ) {}

    /**
     * Chat Dashboard
     */
    public function index()
    {
        $conversations = $this->conversationService
            ->retailerConversations(auth()->id());

        return view(
            'retailer.chat.index',
            compact('conversations')
        );
    }

    /**
     * Create New Conversation
     */
    public function store(StoreConversationRequest $request)
    {
        $dto = ConversationDTO::fromArray([

            'retailer_id' => auth()->id(),

            'admin_id' => $request->admin_id,

        ]);

        $conversation = $this->conversationService
            ->createIfNotExists($dto);

        return redirect()
            ->route(
                'retailer.chat.show',
                $conversation->conversation_id
            )
            ->with(
                'success',
                'Support chat started successfully.'
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

        abort_if(
            $conversation->retailer_id != auth()->id(),
            403
        );

        $conversation->load([
            'messages.sender',
            'admin',
        ]);

        return view(
            'retailer.chat.show',
            compact('conversation')
        );
    }

    /**
     * Close Conversation
     */
    public function close(
        Request $request,
        string $conversationId
    ) {

        $conversation = $this->conversationService
            ->findByUuid($conversationId);

        abort_if(!$conversation, 404);

        abort_if(
            $conversation->retailer_id != auth()->id(),
            403
        );

        $this->conversationService
            ->close($conversation->id);

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
    public function reopen(
        Request $request,
        string $conversationId
    ) {

        $conversation = $this->conversationService
            ->findByUuid($conversationId);

        abort_if(!$conversation, 404);

        abort_if(
            $conversation->retailer_id != auth()->id(),
            403
        );

        $this->conversationService
            ->reopen($conversation->id);

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

    /**
     * Search Conversation
     */
    public function search(Request $request)
    {
        $request->validate([
            'keyword' => ['required', 'string'],
        ]);

        $conversations = $this->conversationService
            ->search(
                $request->keyword
            );

        return view(
            'retailer.chat.index',
            compact('conversations')
        );
    }
}
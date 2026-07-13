@php
    $isAdmin = request()->routeIs('admin.*');
@endphp

<div
    class="chat-body"
    id="chat-body"
>

    @forelse($conversation->messages as $message)

        @include(
            'chat.partials.message',
            [
                'message' => $message,
                'isAdmin' => $isAdmin
            ]
        )

    @empty

        <div class="chat-empty">

            <i class="fas fa-comments fa-4x text-muted mb-3"></i>

            <h5>No Messages Yet</h5>

            <p class="text-muted">

                {{ $isAdmin
                    ? 'No retailer messages yet.'
                    : 'Start chatting with our support team.' }}

            </p>

        </div>

    @endforelse

</div>
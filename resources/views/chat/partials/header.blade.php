@php
    $isAdmin = request()->routeIs('admin.*');
@endphp

<div class="chat-header">

    <div class="chat-user">

        <div class="chat-user-avatar">

            @if($isAdmin)
                <img
                    src="https://ui-avatars.com/api/?name={{ urlencode($conversation->retailer->name) }}"
                    alt="{{ $conversation->retailer->name }}"
                    class="rounded-circle"
                >
            @else
                <img
                    src="https://ui-avatars.com/api/?name=Support"
                    alt="Support Team"
                    class="rounded-circle"
                >
            @endif

            @if($conversation->status === 'active')
                <span class="online-status"></span>
            @endif

        </div>

        <div class="chat-user-info">

            @if($isAdmin)

                <h5 class="mb-1">
                    {{ $conversation->retailer->name }}
                </h5>

                <small class="text-muted">
                    Conversation ID :
                    {{ $conversation->conversation_id }}
                </small>

                <br>

                <small>

                    @if($conversation->admin)

                        Assigned To :
                        <strong>{{ $conversation->admin->name }}</strong>

                    @else

                        <span class="text-warning">
                            Not Assigned
                        </span>

                    @endif

                </small>

            @else

                <h5 class="mb-1">
                    Support Team
                </h5>

                <small class="text-muted">
                    Conversation ID :
                    {{ $conversation->conversation_id }}
                </small>

                <br>

                <small>

                    Status :

                    @if($conversation->status == 'waiting')

                        <span class="badge bg-warning">
                            Waiting
                        </span>

                    @elseif($conversation->status == 'active')

                        <span class="badge bg-success">
                            Active
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            Closed
                        </span>

                    @endif

                </small>

            @endif

        </div>

    </div>

    <div class="chat-actions">

        @if($isAdmin)

            @if(!$conversation->admin_id)

                <form
                    method="POST"
                    action="{{ route('admin.chat.assign',$conversation->conversation_id) }}"
                >
                    @csrf

                    <button class="btn btn-success btn-sm">
                        <i class="fas fa-user-check"></i>
                        Assign Me
                    </button>

                </form>

            @endif

            @if($conversation->status != 'closed')

                <form
                    method="POST"
                    action="{{ route('admin.chat.close',$conversation->conversation_id) }}"
                >
                    @csrf

                    <button class="btn btn-danger btn-sm">
                        <i class="fas fa-lock"></i>
                        Close
                    </button>

                </form>

            @else

                <form
                    method="POST"
                    action="{{ route('admin.chat.reopen',$conversation->conversation_id) }}"
                >
                    @csrf

                    <button class="btn btn-warning btn-sm">
                        <i class="fas fa-lock-open"></i>
                        Reopen
                    </button>

                </form>

            @endif

        @endif

        <button
            class="btn btn-primary btn-sm"
            id="refresh-chat"
        >
            <i class="fas fa-rotate-right"></i>
            Refresh
        </button>

    </div>

</div>
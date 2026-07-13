@php
    $isAdmin = request()->routeIs('admin.*');

    $routePrefix = $isAdmin ? 'admin.chat' : 'retailer.chat';
@endphp

<div class="chat-sidebar">

    <!-- Sidebar Header -->
    <div class="chat-sidebar-header">

        <h5 class="mb-0">

            <i class="fas fa-comments me-2"></i>

            {{ $isAdmin ? 'Support Chats' : 'My Support' }}

        </h5>

    </div>

    <!-- Search -->

    <div class="chat-search">

        <form
            action="{{ route($routePrefix.'.index') }}"
            method="GET"
        >

            <div class="input-group">

                <span class="input-group-text bg-white">

                    <i class="fas fa-search"></i>

                </span>

                <input

                    type="text"

                    name="search"

                    class="form-control"

                    placeholder="{{ $isAdmin ? 'Search retailer...' : 'Search conversation...' }}"

                    value="{{ request('search') }}"

                >

            </div>

        </form>

    </div>

    <!-- Admin Filter -->

    @if($isAdmin)

        <div class="chat-filter">

            <form
                action="{{ route($routePrefix.'.index') }}"
                method="GET"
            >

                <select

                    name="status"

                    class="form-select"

                    onchange="this.form.submit()"

                >

                    <option value="">

                        All Conversations

                    </option>

                    <option
                        value="waiting"
                        @selected(request('status')=='waiting')
                    >
                        Waiting
                    </option>

                    <option
                        value="active"
                        @selected(request('status')=='active')
                    >
                        Active
                    </option>

                    <option
                        value="closed"
                        @selected(request('status')=='closed')
                    >
                        Closed
                    </option>

                </select>

            </form>

        </div>

    @endif

    <!-- Conversation List -->

    <div class="conversation-list">

        @forelse($conversations as $conversation)

            <a

                href="{{ route($routePrefix.'.show',$conversation->conversation_id) }}"

                class="conversation-item

                {{ isset($currentConversation) && $currentConversation->id == $conversation->id ? 'active' : '' }}"

            >

                <div class="conversation-avatar">

                    @if($isAdmin)

                        <img

                            src="https://ui-avatars.com/api/?name={{ urlencode($conversation->retailer->name) }}"

                            class="rounded-circle"

                        >

                    @else

                        <img

                            src="https://ui-avatars.com/api/?name=Support"

                            class="rounded-circle"

                        >

                    @endif

                    @if($conversation->status == 'active')

                        <span class="online-dot"></span>

                    @endif

                </div>

                <div class="conversation-content">

                    <div class="conversation-top">

                        <h6>

                            {{ $isAdmin ? $conversation->retailer->name : 'Support Team' }}

                        </h6>

                        <small>

                            {{ optional($conversation->last_message_at)->diffForHumans() }}

                        </small>

                    </div>

                    <div class="conversation-bottom">

                        <span>

                            {{ \Illuminate\Support\Str::limit($conversation->last_message,40) }}

                        </span>

                        @if(!empty($conversation->unread_count))

                            <span class="badge bg-success rounded-pill">

                                {{ $conversation->unread_count }}

                            </span>

                        @endif

                    </div>

                </div>

            </a>

        @empty

            <div class="text-center py-5">

                <i class="fas fa-comments fa-3x text-muted mb-3"></i>

                <p class="text-muted">

                    No conversations found.

                </p>

            </div>

        @endforelse

    </div>

</div>
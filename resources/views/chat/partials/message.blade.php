@php

    /*
    |--------------------------------------------------------------------------
    | Shared Chat Message
    |--------------------------------------------------------------------------
    */

    $viewerIsAdmin = request()->routeIs('admin.*');

    $isOwnMessage = $viewerIsAdmin
        ? $message->sender_type === 'admin'
        : $message->sender_type === 'retailer';

    $image = $message->attachment &&
             str_starts_with($message->attachment_type ?? '', 'image/');

@endphp

<div
    class="message-row {{ $isOwnMessage ? 'message-right' : 'message-left' }}"
    data-message-id="{{ $message->id }}"
>

    {{-- Left Avatar --}}
    @unless($isOwnMessage)

        <div class="message-avatar">

            <img
                src="https://ui-avatars.com/api/?name={{ urlencode($message->sender->name) }}"
                class="rounded-circle"
                alt="{{ $message->sender->name }}"
            >

        </div>

    @endunless


    {{-- Message Bubble --}}
    <div class="message-bubble {{ $isOwnMessage ? 'admin-message' : 'retailer-message' }}">

        {{-- Sender Name --}}
        <div class="message-header">

            <strong>

                {{ $message->sender->name }}

            </strong>

        </div>

        {{-- Message --}}
        @if(!empty($message->message))

            <div class="message-text">

                {!! nl2br(e($message->message)) !!}

            </div>

        @endif

        {{-- Attachment --}}
        @if($message->attachment)

            <div class="message-attachment mt-2">

                @if($image)

                    <a
                        href="{{ asset('storage/'.$message->attachment) }}"
                        target="_blank"
                    >

                        <img
                            src="{{ asset('storage/'.$message->attachment) }}"
                            class="img-fluid rounded"
                            style="max-width:250px;"
                            alt="Attachment"
                        >

                    </a>

                @else

                    <a
                        href="{{ asset('storage/'.$message->attachment) }}"
                        target="_blank"
                        class="attachment-file"
                    >

                        <i class="fas fa-file me-2"></i>

                        {{ $message->attachment_name }}

                    </a>

                @endif

            </div>

        @endif

        {{-- Footer --}}
        <div class="message-footer">

            <small class="text-muted">

                {{ $message->created_at->format('h:i A') }}

            </small>

            {{-- Show Read Status only for own messages --}}
            @if($isOwnMessage)

                <span class="message-status ms-2">

                    @if($message->read_at)

                        <i class="fas fa-check-double text-primary"></i>

                    @else

                        <i class="fas fa-check text-muted"></i>

                    @endif

                </span>

            @endif

        </div>

    </div>

    {{-- Right Avatar --}}
    @if($isOwnMessage)

        <div class="message-avatar ms-2">

            <img
                src="https://ui-avatars.com/api/?name={{ urlencode($message->sender->name) }}"
                class="rounded-circle"
                alt="{{ $message->sender->name }}"
            >

        </div>

    @endif

</div>
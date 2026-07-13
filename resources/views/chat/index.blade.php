@extends('layout.admin')

@section('title','Support Chat')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endpush

@section('content')

<div id="chat-app"

     data-admin-id="{{ auth()->id() }}"
     
     data-conversation="{{ isset($conversation) ? $conversation->conversation_id : '' }}"

     data-messages-url="{{ isset($conversation) ? route('admin.chat.messages',$conversation->conversation_id) : '' }}"

     data-send-url="{{ isset($conversation) ? route('admin.chat.send',$conversation->conversation_id) : '' }}"

     data-read-url="{{ isset($conversation) ? route('admin.chat.read',$conversation->conversation_id) : '' }}">

    <div class="chat-wrapper">

        @include('admin.chat.partials.sidebar')

        <div class="chat-content">

            @isset($conversation)

                @include('admin.chat.partials.header')

                @include('admin.chat.partials.messages')

                @include('admin.chat.partials.footer')

            @else

                @include('admin.chat.partials.empty')

            @endisset

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script src="{{ asset('js/chat/api.js') }}"></script>
<script src="{{ asset('js/chat/ui.js') }}"></script>
<script src="{{ asset('js/chat/app.js') }}"></script>
@endpush
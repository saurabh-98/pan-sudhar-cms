@extends('layouts.admin')

@section('content')

@include('admin.chat.index')

@endsection

@push('scripts')
    <script src="{{ asset('js/chat/api.js') }}"></script>
    <script src="{{ asset('js/chat/ui.js') }}"></script>
    <script src="{{ asset('js/chat/app.js') }}"></script>

    <script>
        ChatAPI.init({
            conversationId: "{{ $conversation->conversation_id }}",
            messagesUrl: "{{ route('admin.chat.messages', $conversation->conversation_id) }}",
            sendUrl: "{{ route('admin.chat.send', $conversation->conversation_id) }}",
            readUrl: "{{ route('admin.chat.read', $conversation->conversation_id) }}"
        });
    </script>
@endpush
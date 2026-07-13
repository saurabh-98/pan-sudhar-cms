@extends('layout.retailer')

@section('title','Support Chat')

@push('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/chat.css') }}"
>

@endpush

@section('content')

<div class="chat-page">

    <!-- Left Sidebar -->

    <div class="chat-sidebar-wrapper">

        @include('chat.partials.sidebar')

    </div>

    <!-- Right Chat Window -->

    <div class="chat-window-wrapper">

        @if(isset($conversation))

            @include('chat.partials.header')

            @include('chat.partials.messages')

            @include('chat.partials.footer')

        @else

            @include('chat.partials.empty')

        @endif

    </div>

</div>

@endsection

@push('scripts')

<script src="{{ asset('js/chat/helpers.js') }}"></script>

<script src="{{ asset('js/chat/ui.js') }}"></script>

<script src="{{ asset('js/chat/api.js') }}"></script>

<script src="{{ asset('js/chat/uploader.js') }}"></script>

<script src="{{ asset('js/chat/typing.js') }}"></script>

<script src="{{ asset('js/chat/reverb.js') }}"></script>

<script src="{{ asset('js/chat/app.js') }}"></script>

@endpush
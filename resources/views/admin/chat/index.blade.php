@extends('layout.admin')

@section('title','Support Chat')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endpush

@section('content')

@include('chat.partials.sidebar')

@endsection

@push('scripts')
<script src="{{ asset('js/chat/app.js') }}"></script>
@endpush
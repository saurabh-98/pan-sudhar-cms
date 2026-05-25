@extends('layout.app')

@section('content')

<section class="support-wrapper">

    <div class="support-container">

        <div class="support-card">

            <!-- HEADER -->

            <div class="support-header">

                <span class="support-badge">

                    <i class="fa-solid fa-ticket"></i>

                    SUPPORT TRACKING

                </span>

                <h1>

                    Track Support Ticket

                </h1>

                <p>

                    Enter your ticket number and registered email
                    address to view ticket progress and responses.

                </p>

            </div>

            <!-- ERROR -->

            @if(session('error'))

                <div class="track-error">

                    <i class="fa-solid fa-circle-exclamation"></i>

                    {{ session('error') }}

                </div>

            @endif

            <!-- FORM -->

            <form method="POST"
                  action="{{ route('support.search') }}">

                @csrf

                <div class="group">

                    <label>

                        Ticket Number

                    </label>

                    <input type="text"
                           name="ticket_no"
                           placeholder="Enter Ticket Number"
                           value="{{ old('ticket_no') }}"
                           required>

                </div>

                <div class="group">

                    <label>

                        Email Address

                    </label>

                    <input type="email"
                           name="email"
                           placeholder="Enter Registered Email"
                           value="{{ old('email') }}"
                           required>

                </div>

                <button type="submit"
                        class="submit-btn">

                    <i class="fa-solid fa-magnifying-glass"></i>

                    Track Ticket

                </button>

            </form>

            <!-- RESULT -->

            @isset($ticket)

            <div class="ticket-track-wrapper">

                <!-- TOP -->

                <div class="ticket-track-top">

                    <div>

                        <span class="ticket-label">

                            Ticket Number

                        </span>

                        <h2>

                            {{ $ticket->ticket_no }}

                        </h2>

                    </div>

                    <div>

                        <span class="ticket-status
                        {{ $ticket->status }}">

                            {{ strtoupper($ticket->status) }}

                        </span>

                    </div>

                </div>

                <!-- TIMELINE -->

                <div class="ticket-progress">

                    <div class="ticket-progress-bar
                    {{ $ticket->status }}">

                    </div>
                </div>

                <!-- INFO GRID -->

                <div class="ticket-grid">

                    <div class="ticket-info-card">

                        <span>

                            Name

                        </span>

                        <strong>

                            {{ $ticket->name }}

                        </strong>

                    </div>

                    <div class="ticket-info-card">

                        <span>

                            Priority

                        </span>

                        <strong>

                            {{ ucfirst($ticket->priority) }}

                        </strong>

                    </div>

                    <div class="ticket-info-card">

                        <span>

                            Created At

                        </span>

                        <strong>

                            {{ $ticket->created_at->format('d M Y h:i A') }}

                        </strong>

                    </div>

                    <div class="ticket-info-card">

                        <span>

                            Email

                        </span>

                        <strong>

                            {{ $ticket->email }}

                        </strong>

                    </div>

                </div>

                <!-- SUBJECT -->

                <div class="ticket-box">

                    <h4>

                        Subject

                    </h4>

                    <p>

                        {{ $ticket->subject }}

                    </p>

                </div>

                <!-- MESSAGE -->

                <div class="ticket-box">

                    <h4>

                        Message

                    </h4>

                    <p>

                        {{ $ticket->message }}

                    </p>

                </div>

                <!-- ATTACHMENT -->

                @if($ticket->attachment)

                <div class="ticket-box">

                    <h4>

                        Attachment

                    </h4>

                    <a href="{{ asset('storage/' . $ticket->attachment) }}"
                       target="_blank"
                       class="attachment-btn">

                        <i class="fa-solid fa-paperclip"></i>

                        View Attachment

                    </a>

                </div>

                @endif

                <!-- ADMIN REPLY -->

                <div class="admin-reply-box">

                    <div class="reply-header">

                        <i class="fa-solid fa-headset"></i>

                        Support Team Reply

                    </div>

                    <div class="reply-content">

                        @if($ticket->admin_reply)

                            {{ $ticket->admin_reply }}

                        @else

                            Your ticket is currently under review.
                            Our support team will respond shortly.

                        @endif

                    </div>

                </div>

            </div>

            @endisset

        </div>

    </div>

</section>

@endsection
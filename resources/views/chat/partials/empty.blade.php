<div class="chat-empty-screen">

    <div class="chat-empty-content">

        <img
            src="{{ asset('images/support-agent.png') }}"
            class="support-image"
        >

        <h2>

            Welcome to Support Chat

        </h2>

        <p>

            Need help with PAN, Aadhaar, Wallet,
            KYC or any service?

            <br>

            Our support team is available.

        </p>

        <form
            method="POST"
            action="{{ route('retailer.chat.store') }}"
        >

            @csrf

            <button
                class="btn btn-primary btn-lg"
            >

                <i class="fa fa-comments"></i>

                Start New Conversation

            </button>

        </form>

    </div>

</div>
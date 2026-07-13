document.addEventListener('DOMContentLoaded', function () {

    const chat = document.getElementById('chat-app');

    if (!chat) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | API
    |--------------------------------------------------------------------------
    */

    ChatAPI.init({

        conversationId: chat.dataset.conversation,

        messagesUrl: chat.dataset.messagesUrl,

        sendUrl: chat.dataset.sendUrl,

        readUrl: chat.dataset.readUrl

    });

    /*
    |--------------------------------------------------------------------------
    | UI
    |--------------------------------------------------------------------------
    */

    ChatUI.init();

    ChatUploader.init();

    ChatTyping.init();

    /*
    |--------------------------------------------------------------------------
    | Realtime / Polling
    |--------------------------------------------------------------------------
    */

    if (typeof Echo !== "undefined") {

        ChatRealtime.init({

            conversationId: chat.dataset.conversation,

            key: chat.dataset.reverbKey,

            host: window.location.hostname,

            port: 8080,

            forceTLS: false

        });

    } else {

        console.warn("Laravel Echo not found. Using Polling.");

        ChatPolling.start();

    }

});
window.ChatRealtime = (function () {

    let echo = null;

    let channel = null;

    /**
     * Initialize Reverb
     */
    function init(config) {

        if (typeof window.Echo === "undefined") {

            window.Echo = new Echo({

                broadcaster: "reverb",

                key: config.key,

                wsHost: config.host,

                wsPort: config.port,

                wssPort: config.port,

                forceTLS: config.forceTLS,

                enabledTransports: ["ws", "wss"]

            });

        }

        echo = window.Echo;

        subscribe(config.conversationId);

    }

    /**
     * Subscribe Conversation
     */
    function subscribe(conversationId) {

        channel = echo.private(
            "conversation." + conversationId
        );

        listen();

    }

    /**
     * Listen Events
     */
    function listen() {

        /*
        |--------------------------------------------------------------------------
        | Message Sent
        |--------------------------------------------------------------------------
        */

        channel.listen(".message.sent", function (event) {

            ChatUI.appendMessage(event.message);

            ChatHelper.scrollBottom();

        });

        /*
        |--------------------------------------------------------------------------
        | Message Read
        |--------------------------------------------------------------------------
        */

        channel.listen(".message.read", function (event) {

            document
                .querySelectorAll(".message-status i")
                .forEach(function (icon) {

                    icon.classList.remove("fa-check");

                    icon.classList.add("fa-check-double");

                    icon.classList.add("text-primary");

                });

        });
        
        /*
        |--------------------------------------------------------------------------
        | Typing
        |--------------------------------------------------------------------------
        */

        channel.listen(".typing", function (event) {

            const typing = document.getElementById(
                "typing-indicator"
            );

            if (!typing) return;

            typing.innerHTML =
                event.user + " is typing...";

            clearTyping();

        });

    }

    /**
     * Clear Typing
     */
    function clearTyping() {

        setTimeout(function () {

            const typing = document.getElementById(
                "typing-indicator"
            );

            if (typing) {

                typing.innerHTML = "";

            }

        }, 1500);

    }

    /**
     * Leave Channel
     */
    function disconnect() {

        if (!channel) {

            return;

        }

        echo.leaveChannel(
            "private-conversation." +
            channel.name
        );

    }

    return {

        init,

        disconnect

    };

})();
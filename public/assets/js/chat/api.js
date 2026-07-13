window.ChatAPI = (function () {

    let conversationId = null;

    let routes = {
        messages: '',
        send: '',
        read: '',
    };

    /**
     * Initialize
     */
    function init(config)
    {
        conversationId = config.conversationId;

        routes.messages = config.messagesUrl;

        routes.send = config.sendUrl;

        routes.read = config.readUrl;
    }

    /**
     * Get CSRF Token
     */
    function csrf()
    {
        return document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content');
    }

    /**
     * Load Messages
     */
    async function loadMessages()
    {
        try {

            const response = await fetch(routes.messages, {

                headers: {

                    "Accept": "application/json"

                }

            });

            return await response.json();

        }
        catch (error) {

            console.error(error);

        }
    }

    /**
     * Send Message
     */
    async function sendMessage(form)
    {
        try {

            const response = await fetch(routes.send, {

                method: "POST",

                headers: {

                    "X-CSRF-TOKEN": csrf(),

                    "Accept": "application/json"

                },

                body: form

            });

            return await response.json();

        }
        catch (error) {

            console.error(error);

        }
    }

    /**
     * Mark Read
     */
    async function markAsRead()
    {
        try {

            const response = await fetch(routes.read, {

                method: "POST",

                headers: {

                    "X-CSRF-TOKEN": csrf(),

                    "Accept": "application/json"

                }

            });

            return await response.json();

        }
        catch (error) {

            console.error(error);

        }
    }

    /**
     * Delete Message
     */
    async function deleteMessage(url)
    {
        try {

            const response = await fetch(url, {

                method: "DELETE",

                headers: {

                    "X-CSRF-TOKEN": csrf(),

                    "Accept": "application/json"

                }

            });

            return await response.json();

        }
        catch (error) {

            console.error(error);

        }
    }

    return {

        init,

        loadMessages,

        sendMessage,

        markAsRead,

        deleteMessage

    };

})();
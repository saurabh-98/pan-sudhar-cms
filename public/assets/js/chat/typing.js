window.ChatTyping = (function () {

    let timer;

    function init() {

        const input = document.getElementById('message');

        if (!input)
            return;

        input.addEventListener(
            'input',
            typing
        );

    }

    function typing() {

        clearTimeout(timer);

        document
            .getElementById('typing-indicator')
            .innerHTML = "Typing...";

        timer = setTimeout(function () {

            document
                .getElementById('typing-indicator')
                .innerHTML = "";

        }, 1000);

    }

    return {

        init

    };

})();
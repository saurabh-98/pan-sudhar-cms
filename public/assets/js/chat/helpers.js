window.ChatHelper = (function () {

    function scrollBottom() {

        const body = document.getElementById('chat-body');

        if (!body) return;

        body.scrollTop = body.scrollHeight;

    }

    function escapeHtml(text) {

        const div = document.createElement('div');

        div.innerText = text;

        return div.innerHTML;

    }

    function toast(message, type = "success") {

        console.log(type.toUpperCase(), message);

        /*
            Later replace with

            Toastify

            SweetAlert

            Bootstrap Toast
        */

    }

    function loading(button, show = true) {

        if (!button)
            return;

        if (show) {

            button.disabled = true;

            button.dataset.old = button.innerHTML;

            button.innerHTML =
                '<span class="spinner-border spinner-border-sm"></span>';

        } else {

            button.disabled = false;

            button.innerHTML =
                button.dataset.old;

        }

    }

    return {

        scrollBottom,

        escapeHtml,

        toast,

        loading

    };

})();
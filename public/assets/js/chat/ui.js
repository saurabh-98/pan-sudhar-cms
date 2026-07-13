window.ChatUI = (function () {

    let body;
    let form;
    let textarea;

    function init()
    {
        body = document.getElementById('chat-body');

        form = document.getElementById('chat-form');

        textarea = document.getElementById('message');

        registerEvents();
    }

    function registerEvents()
    {
        if(form){

            form.addEventListener(
                'submit',
                sendMessage
            );

        }

        if(textarea){

            textarea.addEventListener(
                'input',
                updateCounter
            );

            textarea.addEventListener(
                'input',
                autoResize
            );

        }
    }

    async function loadMessages()
    {
        const response = await ChatAPI.loadMessages();

        if(!response.success){

            return;

        }

        body.innerHTML = '';

        response.messages.forEach(function(message){

            appendMessage(message);

        });

        scrollBottom();

        ChatAPI.markAsRead();
    }

    async function sendMessage(e)
    {
        e.preventDefault();

        const formData = new FormData(form);

        const response = await ChatAPI.sendMessage(formData);

        if(!response.success){

            alert(response.message);

            return;

        }

        appendMessage(response.message);

        form.reset();

        document
            .getElementById('attachment-preview')
            ?.classList.add('d-none');

        scrollBottom();
    }

    function appendMessage(message)
    {
        body.insertAdjacentHTML(
            'beforeend',
            bubble(message)
        );
    }

    function bubble(message)
    {
        const adminId = document
            .getElementById('chat-app')
            .dataset
            .adminId;

        const right = message.sender_id == adminId;

        return `
<div class="message-row ${right?'message-right':'message-left'}">

<div class="message-bubble ${right?'admin-message':'retailer-message'}">

<div class="message-header">

<strong>${message.sender.name}</strong>

</div>

<div class="message-text">

${escapeHtml(message.message ?? '')}

</div>

<div class="message-footer">

${message.created_at}

</div>

</div>

</div>`;
    }

    function updateCounter()
    {
        const counter = document.getElementById(
            'character-count'
        );

        if(counter){

            counter.innerHTML =
                textarea.value.length + " / 5000";

        }
    }

    function autoResize()
    {
        textarea.style.height = 'auto';

        textarea.style.height =
            textarea.scrollHeight + 'px';
    }

    function scrollBottom()
    {
        body.scrollTop = body.scrollHeight;
    }

    function escapeHtml(text)
    {
        const div = document.createElement('div');

        div.textContent = text;

        return div.innerHTML;
    }

    return {

        init,

        loadMessages,

        appendMessage,

        scrollBottom

    };

})();
window.ChatPolling = (function(){

    let timer;

    function start(){

        timer = setInterval(function(){

            ChatUI.loadMessages();

        },3000);

    }

    function stop(){

        clearInterval(timer);

    }

    return {

        start,

        stop

    };

})();
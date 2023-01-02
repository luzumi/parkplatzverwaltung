{
    function showThread(threadId) {
        $('#subject').css('display', 'flex');
        $('#messages').empty();
        $('#form').css('display', 'block');

        // Abrufen aller Nachrichten des Threads mithilfe von AJAX
        // Abrufen aller Nachrichten des Threads mithilfe von AJAX
        $.ajax({
            url: '/messages/' + threadId,
            success: function (data) {
                $('#thread-subject').text(data.subject);

                // Anzeigen aller Nachrichten im rechten Teil der Chatansicht
                data.messages.forEach(function (message) {
                    // Bestimmen, ob die Nachricht von Auth::User oder von einem anderen Benutzer gesendet wurde

                    const isCurrentUser = message.sender === document.body.dataset.currentUserName;

                    const messageClass = isCurrentUser ? 'from-current-user' : 'from-other-user';
                    const sender = `<strong>${message.sender}:</strong>`;

                    // Anzeigen der Nachricht mit der entsprechenden Ausrichtung
                    const messageHTML = `
                <div class="message ${messageClass}">
                    ${isCurrentUser ? message.body + ' ' + sender : sender + ' ' + message.body}
                <hr>
                </div>`;
                    $('#messages').append(messageHTML);

                });
                // Chatverlauf nach unten scrollen, nachdem die Nachricht angezeigt wurde
                scrollToBottom();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.status + ': ' + errorThrown);
            }
        });

    }

    function scrollToBottom() {
        const chatMessages = document.getElementById('messages');
        chatMessages.scrollTo(0, chatMessages.scrollHeight);
    }

    const tabButtons = document.querySelectorAll('.tab-btn');

    function showTab() {
        const chatElement = document.getElementById('chat');
        const newMessageElement = document.getElementById('new-message');
        const tabChatBtn = document.getElementById('tabChatBtn');
        const tabNewMessageBtn = document.getElementById('tabNewMessageBtn');

        if (chatElement && newMessageElement && tabChatBtn && tabNewMessageBtn) {
            if (chatElement.style.display === 'block') {
                chatElement.style.display = 'none';
                newMessageElement.style.display = 'block';

                tabChatBtn.classList.remove('active');
                tabNewMessageBtn.classList.add('active');
            } else {
                chatElement.style.display = 'block';
                newMessageElement.style.display = 'none';

                tabChatBtn.classList.add('active');
                tabNewMessageBtn.classList.remove('active');
            }
        }
    }

    tabButtons.forEach(function (btn) {
        btn.addEventListener('click', function (event) {
            showTab(event.target.dataset.tab);
        });
    });


    window.onload = function () {
        // show default tab
        showTab('#chat');
        // Wandle last_thread_id in eine JavaScript-Variable um

        showThread(document.body.dataset.lastThreadId);
    };
}

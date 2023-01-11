@extends('layouts.admin')

@section('content')
    @include('admin.messenger.partials.flash')
    <div class="container mt-3 h-75">
        <div class="tabs">
            <button id="adminTabChatBtn" class="tab-btn active" data-tab="#chat" onclick="showTab()">Chat</button>
            <button id="adminTabNewMessageBtn" class="tab-btn" data-tab="#new-message" onclick="showTab()">Neue
                Nachricht
            </button>
        </div>


        <div id="admin-chat">
            <!-- chat area goes here -->

            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header font-weight-bold">Chats</div>
                        <ul class="list-group list-group-flush">
                            @if (count($threads) > 0)
                                @foreach($threads as $thread)
                                    <li class="list-group-item m-0">
                                        <a href="#" onclick="showThread({{ $thread->id }})">
                                            <div class="row">
                                                <div class="col-4">
                                                    <img
                                                        src="{{ asset('storage/media/' . $thread->users[1]->image) }}"
                                                        class="col-12 rounded-circle align-self-center mr-2"
                                                        alt="User image">
                                                </div>
                                                <div class="col">
                                                    <h5><span
                                                            class="mb-1">{{ $thread->users[1]->getAttribute('name')}}</span>
                                                        <span
                                                            class="mb-3 text-sm-end">{{substr($thread->subject, 0, 16) . '...'}}</span>
                                                    </h5>
                                                    <small
                                                        class="mb-4 text-xs">{{ substr($thread->latestMessage->body, 0, 20) . '...' }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="w-100">
                        <div id="admin-subject" class="card mb-3 last-message h-75">
                            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                                <div id="admin-thread-subject"></div>
                            </div>
                            <div
                                class="card-body font-weight-bold d-flex justify-content-between align-items-center h-75">
                                <div class="row w-100">
                                    <div class="chat-messages ">
                                        <div id="admin-messages" class="messageView w-100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="admin-form">
                        <form id="updateMessageForm" method="post">
                            {{method_field('put')}}
                            {{ csrf_field() }}
                            <input type="hidden" name="threadId" id="threadId" value="">

                            <!-- Subject Form Input -->
                            <div class="form-group">
                                <label id="subject" for="subject"></label>
                            </div>

                            <!-- Message Form Input -->
                            <div class="form-group">
                                <label id="messageTo"></label>
                                <textarea name="message" class="form-control" id="message" placeholder="Nachricht..."></textarea>
                            </div>

                            <!-- Submit Form Input -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary form-control">Senden</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="admin-new-message" class="tab-content" style="display:none">
            <!-- new message form goes here -->

            @include('admin.messenger.create')

        </div>
    </div>
@stop


<script>
    let currentThreadId;

    function showThread(threadId) {
        $('#admin-subject').css('display', 'flex');
        $('#admin-messages').empty();
        $('#admin-form').css('display', 'block');
        const id = document.getElementById('threadId');
        const subject = document.getElementById('subject');
        const messageTo = document.getElementById('messageTo');

        const form = document.getElementById('updateMessageForm');
        id.value = threadId;
        form.setAttribute('action', '/admin/messages/update/' + id.value);

        const messageField = document.getElementById('message');

        messageField.addEventListener('keypress', function (event){
            console.log(event)
            if (event.code === "Enter" && !event.shiftKey) {
                form.submit();
            }else if(event.code === "Enter" && event.shiftKey){
                form.setAttribute('message', form.getAttribute('message') + "<br>");
            }
        })

        // Abrufen aller Nachrichten des Threads mithilfe von AJAX
        $.ajax({
            url: '/admin/messages/' + threadId,
            data: {
                _token: '{{ csrf_token() }}',
                id: currentThreadId
            },
            success: function (data) {
                $('#admin-thread-subject').text(data.subject);
                // Anzeigen aller Nachrichten im rechten Teil der Chatansicht
                console.dir(data);
                data.messages.forEach(function (message) {
                    // Bestimmen, ob die Nachricht von Auth::User oder von einem anderen Benutzer gesendet wurde
                    const isCurrentUser = message.sender === '{{ Auth::user()->getAttribute('name') }}';
                    const messageClass = isCurrentUser ? 'from-current-user' : 'from-other-user';
                    const sender = `<strong>${message.sender}:</strong>`;

                    subject.textContent = "Betreff: " + data.subject;
                    messageTo.textContent = 'Antworten: ';
                    // Anzeigen der Nachricht mit der entsprechenden Ausrichtung
                    const messageHTML = `<div class="message ${messageClass}">${sender + '<br>' + message.body}
                        <hr>
                        </div>`;
                    $('#admin-messages').append(messageHTML);
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
        const chatMessages = document.getElementById('admin-messages');
        chatMessages.scrollTo(0, chatMessages.scrollHeight);
    }

    const tabButtons = document.querySelectorAll('.tab-btn');

    function showTab() {
        const chatElement = document.getElementById('admin-chat');
        const newMessageElement = document.getElementById('admin-new-message');
        const tabChatBtn = document.getElementById('adminTabChatBtn');
        const tabNewMessageBtn = document.getElementById('adminTabNewMessageBtn');
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
        showTab('#admin-chat');
        // Wandle last_thread_id in eine JavaScript-Variable um
        showThread({{Auth::user()->last_thread_id}});
    };

    const messageField = document.getElementById('message');
    messageField.addEventListener('keypress', function (event){
        console.log(event)
        if(event.code === 'Enter'){
            form.submit();
        }
    })
</script>

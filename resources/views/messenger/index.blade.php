@extends('welcome')

@section('content')
    @include('messenger.partials.flash')
    <div class="container mt-3 h-75">
        <div class="tabs">
            <button class="tab-btn active" data-tab="#chat">Chat</button>
            <button class="tab-btn" data-tab="#new-message">Neue Nachricht</button>
        </div>


        <div id="chat" class="tab-content">
            <!-- chat area goes here -->

            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header font-weight-bold">Chats</div>
                        <ul class="list-group list-group-flush">
                            @foreach($threads as $thread)
                                <li class="list-group-item m-0">
                                    <a href="#" onclick="showThread({{ $thread->id }})">
                                        <div class="row">
                                            <div class="col-2">
                                                <img
                                                    src="{{ asset('storage/media/' . $thread->users[1]->image) }}"
                                                    class="col-12 rounded-circle align-self-center mr-2"
                                                    alt="User image">
                                            </div>
                                            <div class="col">
                                                <h5><span
                                                        class="mb-1">{{$thread->users[1]->getAttribute('name')}}</span>
                                                    <span class="mb-3 text-sm-end">{{$thread->subject}}</span>
                                                </h5>
                                                <small
                                                    class="mb-4 text-xs">{{ substr($thread->latestMessage->body, 0, 30) . '...' }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="w-100">
                        <div id="subject" class="card mb-3 last-message h-75" style="display:none">
                            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                                <div id="thread-subject"></div>
                            </div>
                            <div
                                class="card-body font-weight-bold d-flex justify-content-between align-items-center h-75">
                                <div class="row w-100">
                                    <div class="chat-messages ">
                                        <div id="messages" class="messageView w-100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="form" style="display:none">
                        <form action="{{ route('messages.update', $thread->id) }}" method="post">
                            {{ method_field('put') }}
                            {{ csrf_field() }}

                            <!-- Message Form Input -->
                            <div class="form-group w-100">
                                <label>
                                    <textarea name="message" class="form-control" placeholder="Antwort:"></textarea>
                                </label>
                            </div>

                            <label title="{{ $thread->users[1]->getAttribute('name') }}">
                                <input type="hidden" name="recipients[]" value="{{ $thread->users[1]->id }}" checked>
                            </label>

                            <!-- Submit Form Input -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary form-control">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="new-message" class="tab-content" style="display:none">
            <!-- new message form goes here -->
        </div>
    </div>

@stop


<script>
    function showThread(threadId) {
        $('#subject').css('display', 'flex');
        $('#messages').empty();
        $('#form').css('display', 'block');


        // Abrufen aller Nachrichten des Threads mithilfe von AJAX
        $.ajax({
            url: '/messages/' + threadId,
            success: function (data) {
                $('#thread-subject').text(data.subject);

                // Anzeigen aller Nachrichten im rechten Teil der Chatansicht
                data.messages.forEach(function (message) {
                    // Bestimmen, ob die Nachricht von Auth::User oder von einem anderen Benutzer gesendet wurde
                    const isCurrentUser = message.sender === '{{ Auth::user()->getAttribute('name') }}';
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

    function showTab(tab) {
        // hide all tab content
        document.querySelectorAll('.tab-content').forEach(function(content) {
            content.classList.remove('active');
        });

        // remove active class from all buttons
        tabButtons.forEach(function(btn) {
            btn.classList.remove('active');
        });

        // show selected tab content
        document.querySelector(tab).classList.add('active');

        // add active class to selected button
        event.target.classList.add('active');
    }

    tabButtons.forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            showTab(event.target.dataset.tab);
        });
    });

    // show default tab
    showTab('#chat');

</script>

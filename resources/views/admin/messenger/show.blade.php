@extends('layouts.admin')

@section('content')
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <ul class="list-group list-group-flush">
                        @foreach($threads as $thread)
                            <li class="list-group-item">
                                <a href="{{ route('admin.messages.show', $thread->id) }}" onclick="showThread({{ $thread->id }})">
                                <div class="row">
                                        <div class="col-2">
                                            <img src="{{ asset('storage/media/' . $thread->users[1]->image) }}" class="col-12 rounded-circle align-self-center mr-2" alt="User image">
                                        </div>
                                        <div class="col">
                                            <h5 class="mb-1">{{ $thread->creator()->getAttribute('name')}}</h5>
                                            <small class="mb-4 text-xs">{{ substr($thread->latestMessage->body, 0, 30) . '...' }}</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header font-weight-bold d-flex justify-content-between align-items-center" id="thread-subject">
                        Last message
                        <span class="badge badge-secondary">{{ $thread->updated_at->format('d.m.Y H:i') }}</span>
                    </div>
                    <div class="card-body p-4" id="messages">
                        <!-- Messages for the selected thread will be displayed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function showThread(threadId) {
            // Abrufen des Thread-Bodys mit Hilfe von AJAX
            $.ajax({
                url: '/admin/messages/' + threadId,
                data: {},
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
                    console.log('ERROR:' + jqXHR.status + ': ' + errorThrown);
                }
            });
        }
    </script>
@stop

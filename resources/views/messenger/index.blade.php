@extends('welcome')

@section('content')
    @include('messenger.partials.flash')
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header font-weight-bold">Chats</div>
                    <ul class="list-group list-group-flush">
                    @foreach($threads as $thread)
{{--                            {{dd($thread)}}--}}
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
                                            <h5><span class="mb-1">{{$thread->users[1]->getAttribute('name')}}</span>
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
                    <div id="subject" class="card mb-3 last-message" style="display:none">
                        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                            <div id="thread-subject"></div>
                        </div>
                        <div class="card-body font-weight-bold d-flex justify-content-between align-items-center">
                            <div class="row">
                                <div class="chat-messages">
                                    <div id="messages"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <script>
        function showThread(threadId) {
            $('#subject').css('display', 'flex');

            // Abrufen aller Nachrichten des Threads mit Hilfe von AJAX
            $.ajax({
                url: '/messages/' + threadId,
                success: function (data) {
                    $('#thread-subject').text(data.subject);
                    // Anzeigen aller Nachrichten im rechten Teil der Chatansicht
                    data.messages.forEach(function (message) {
                        console.log(message);
                        // Bestimmen, ob die Nachricht von auth:user oder von einem anderen Benutzer gesendet wurde
                        var messageClass = 'from-other-user';
                        if (message.sender === '{{ Auth::user()->getAttribute('name') }}') {
                            messageClass = 'from-current-user';
                        }

                        // Anzeigen der Nachricht mit der entsprechenden Ausrichtung
                        $('#messages').append(`
                    <div class="message ${messageClass}">
                        <strong>${message.sender}:</strong> ${message.body}<hr>
                    </div>
                `);
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.status + ': ' + errorThrown);
                }
            });
        }

    </script>
@stop

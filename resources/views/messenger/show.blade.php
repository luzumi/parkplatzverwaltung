{{--@extends('welcome')--}}

{{--@section('message_content')--}}
{{--    <div class="col-md-6">--}}
{{--        <h1>{{ $thread->subject }}</h1>--}}
{{--        @each('messenger.partials.messages', $thread->messages, 'message')--}}

{{--        @include('messenger.partials.form-message')--}}
{{--    </div>--}}
{{--@stop--}}
@extends('welcome')

@section('content')
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <ul class="list-group list-group-flush">
                        @foreach($threads as $thread)
                            <li class="list-group-item">
                                <a href="{{ route('messages.show', $thread->id) }}" onclick="showThread({{ $thread->id }})">
                                <div class="row">
                                        <div class="col-2">
                                            <img src="{{ asset('storage/media/' . $thread->users[1]->image) }}" class="col-12 rounded-circle align-self-center mr-2" alt="User image">
                                        </div>
                                        <div class="col">
                                            <h5 class="mb-1">{{$thread->creator()->getAttribute('name')}}</h5>
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
                url: '/messages/' + threadId,
                success: function(data) {
                    // Anzeigen des Thread-Bodys im rechten Teil der Chatansicht
                    $('#thread-subject').text(data.subject);
                    $('#messages').html(data.body);
                }
            });
        }
    </script>
@stop

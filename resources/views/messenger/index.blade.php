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

                            <li class="list-group-item m-0">
                                <a href="{{ route('messages.show', $thread->id) }}">
                                    <div class="row">
                                        <div class="col-2">
                                            <img
                                                src="{{ asset('storage/media/' . $thread->users[1]->image) }}"
                                                class="col-12 rounded-circle align-self-center mr-2"
                                                alt="User image">
                                        </div>
                                        <div class="col">
                                            <h5 class="mb-1">{{$thread->creator()->getAttribute('name')}}</h5>
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
                <div class="col-md-9">
                    @if ($latestMessage)
                        <div class="card mb-3">
                            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                                {{ $latestMessage->thread->subject }}
                                <span
                                    class="badge badge-secondary">{{ $latestMessage->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <div class="card-body">
                                {{ $latestMessage->body }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

<div class="card mb-3">
    <div class="card-header font-weight-bold d-flex justify-content-between align-items-center" id="thread-subject">
        {{ $thread->subject }}
        <span class="badge badge-secondary">{{ $thread->updated_at->format('d.m.Y H:i') }}</span>
    </div>
    <div class="card-body">
        @foreach($thread->messages as $message)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ $message->user->name }}
                    <span class="badge badge-secondary">{{ $message->created_at->format('d.m.Y H:i') }}</span>
                </div>
                <div class="card-body">
                    {{ $message->body }}
                </div>
            </div>
        @endforeach
    </div>
</div>

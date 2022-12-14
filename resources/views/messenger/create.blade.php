@extends('welcome')

@section('content')
    <h1>Create a new message</h1>
    <form action="{{ route('messages.store') }}" method="post">
        @csrf
        {{--        {{dd($users)}}--}}
        <div class="col-md-6">
            <!-- Subject Form Input -->
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    Senden an:
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    @foreach($users as $user)
                        <li><a class="dropdown-item" href="#">{{ $user->getAttribute('name') }}</a></li>
                    @endforeach
                </ul>
            </div>
            {{--            <!-- Message Form Input -->--}}
            {{--            <div class="form-group">--}}
            {{--                <label class="control-label">Message</label>--}}
            {{--                <textarea name="message" class="form-control">{{ old('message') }}</textarea>--}}
            {{--            </div>--}}

            {{--            @if($users->count() > 0)--}}
            {{--                <div class="checkbox">--}}
            {{--                    @foreach($users as $user)--}}
            {{--                        <label>{{ $user->getAttribute('name') }}<input type="checkbox" name="recipients[]"--}}
            {{--                                                                       value="{{ $user->id }}">{!!$user->name!!}</label>--}}
            {{--                    @endforeach--}}
            {{--                </div>--}}
            {{--            @endif--}}

            {{--            <!-- Submit Form Input -->--}}
            {{--            <div class="form-group">--}}
            {{--                <button type="submit" class="btn btn-primary form-control">Submit</button>--}}
            {{--            </div>--}}
        </div>
    </form>
@stop


<h1>Create a new message</h1>
<form action="{{ route('admin.messages.store') }}" method="post">
    @csrf
    {{--        {{dd($users)}}--}}
    <div class="col-md-6">
        <!-- Subject Form Input -->
        <div class="form-group">
            <label class="control-label">subject</label>
            <label>
                <textarea name="subject" class="form-control">{{ old('message') }}</textarea>
            </label>
        </div>
        <!-- Message Form Input -->
        <div class="form-group">
            <label class="control-label">Message</label>
            <label>
                <textarea name="message" class="form-control">{{ old('message') }}</textarea>
            </label>
        </div>
            <div class="checkbox">
                @foreach(\App\Models\User::all()->where('id', '!=', \Illuminate\Support\Facades\Auth::id()) as $user)
                    <label>{{ \Illuminate\Support\Facades\Auth::user()->getAttribute('name') }}
                        an <b> {{$user->getAttribute('name')}} </b>
                        <input type="checkbox" name="recipients[]"
                               value="{{ $user->id }}">
                    </label>
                @endforeach
            </div>

        <!-- Submit Form Input -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary form-control">Submit</button>
        </div>
    </div>
</form>

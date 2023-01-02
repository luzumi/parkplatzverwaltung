

    <h1>Create a new message</h1>
    <form action="{{ route('messages.store') }}" method="post">
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

{{--                        @if($users->count() > 0)--}}
{{--                            <div class="checkbox">--}}
{{--                                @foreach($users as $user)--}}
                                    <label>{{ \Illuminate\Support\Facades\Auth::user()->getAttribute('name') }}<input type="hidden" name="recipients[]"
                                                                                                                      value="{{ \App\Models\User::where('role','=','admin')->first('id') }}"> an <b> Administrator</b></label>
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                        @endif--}}

                        <!-- Submit Form Input -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary form-control">Submit</button>
                        </div>
        </div>
    </form>


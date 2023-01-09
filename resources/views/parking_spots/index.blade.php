@extends("layouts.app")

@section("title", $viewData['title'])
@section("subtitle", $viewData['subtitle'])

@section("content")
    <div class="row">
        @foreach($viewData["parking_spots"] as $parking_spot)
            <div class="col-md-4 col-lg-3 mb-2">
                <div class="card">
                    <img src="{{ asset('/storage/media/'.$parking_spot->image) }}" class="card-img-top img-card
                    {{ $parking_spot->status ? 'bg-danger' : 'opacity-25' }}" alt="image not found">
                    <div class="card-body text-center ">
                        @if( Auth::check() )
                            <a href="{{ route('parking_spots.show', ['id'=> $parking_spot->id]) }}"
                               class="btn {{ $parking_spot->switchStatus() }} text-white">
                                Parkplatz {{ $parking_spot->number }}
                                {{ $parking_spot->getStatusMessage() }}
                            </a>
                        @else
                            <p class="{{ $parking_spot->switchStatus() }} text-white>"
                            > Parkplatz {{ $parking_spot->number }}
                                {{ $parking_spot->getStatusMessage() }}
                            </p>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

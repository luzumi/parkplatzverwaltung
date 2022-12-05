@php use Illuminate\Support\Facades\Auth; @endphp
@extends('layouts.app')
@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])

@section("content")
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="{{ asset('/storage/media/'. $viewData['parking_spot']->image) }}"
                     class="img-fluid rounded-start" alt="Image not found">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">
                        Parkplatz {{ $viewData["parking_spot"]->number }}, Reihe
                        {{ $viewData["parking_spot"]->row }},<br>
                        {{ $viewData["parking_spot"]->getStatusMessage() }}
                        | {{ $viewData["parking_spot"]->status}}
                    </h5>

                    @if($viewData["parking_spot"]->status == 'frei')
                        <form method="POST"
                              action="{{ route('parking_spots.storeThisCar') }}"
                              enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <h5 class="card-title">
                                Name: {{ $viewData["user"]->name }} <br><br>
                                Fahrzeuge:
                                <table class="table table-bordered">
                                    <tr class="table-primary">
                                        <th>Auswahl</th>
                                        <th>Kennzeichen</th>
                                        <th>Hersteller</th>
                                        <th>Model</th>
                                        <th>Farbe</th>
                                        <th>Vorschau</th>
                                    </tr>
{{-- Auflistung der Fahrzeuge des Users--}}
                                    @foreach($viewData['cars'] as $car)
                                        <tr class="table-active">
{{-- Wenn Fahrzeug bereits einen Parkplatz reserviert hat wird ein Button zum Löschen angezeigt, statt des Buttons--}}
                                        @if(!isset($car->parkingSpot))

                                                <td>
                                                    <div class="status">
                                                        <label class="input-group-sm">
{{--                                                            <input type="radio" id='{{ $car->id }}' name="car_id" value="{{ $car->id }}">--}}
                                                            <a href="{{ route('cars.show', ['id'=> $car->id]) }}">
                                                                <p class="btn btn-primary {{ $viewData["parking_spot"]->switchStatus() }} text-white ">Zur Parkplatzreservierung</p>
                                                            </a>
                                                        </label>
                                                    </div>
                                                </td>
                                            @else
                                                <td>
                                                    <a href="{{ route('parking_spots.reserve.cancel', $car->parkingSpot->id) }}"
                                                       class="btn btn-danger {{ $viewData["parking_spot"]->switchStatus() }} text-white ">
                                                        <p class="pe-lg-4">Reservierung Parkplatz&nbsp;{{$car->parkingSpot->number}}&nbsp;löschen</p>
                                                    </a>
                                                </td>
                                            @endif
                                            <td>{{ $car->sign }}</td>
                                            <td>{{ $car->manufacturer }}</td>
                                            <td>{{ $car->model }}</td>
                                            <td>{{ $car->color }}</td>
                                            <td><img src="{{ asset('/storage/media/'. $car->image) }}"
                                                     class="img-thumbnail col-sm-6" alt="{{ asset('/storage/media/testCar.png') }}"></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </h5>

                            <button type="submit" class="btn btn-primary">Absenden</button>
                        </form>
                    @endif
                    <div>
                        <p class="card-text"><small class="text-muted">***********************************************</small></p>
                        <p class="card-text">Letzte Änderung: {{ $viewData["parking_spot"]->updated_at }}</p>
                        <p class="card-text"><small class="text-muted">***********************************************</small></p>
                        <a href="{{ route('user.show', Auth::id()) }}"
                           class="btn {{ $viewData["parking_spot"]->switchStatus() }} text-white offset-lg-7">
                            zurück zur Übersicht
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

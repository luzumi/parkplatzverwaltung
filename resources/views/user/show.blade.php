@php use App\Models\Car;use App\Models\ParkingSpot;use App\Models\User; @endphp
@extends('welcome')
@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])
@section('content')
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4">
                    <img src="{{ asset('/storage/media/'. $viewData['user']->image) }}"
                         class="img-card rounded-start"
                         alt="">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">
                        Name: {{ $viewData["user"]->name }} <br>
                        eMail: {{ $viewData["user"]->email }} <br>
                        Telefon: {{ $viewData["user"]->telefon }} <br>

                        Land: {{ $viewData["address"]->Land }} <br>
                        PLZ: {{ $viewData["address"]->PLZ }} <br>
                        Stadt: {{ $viewData["address"]->Stadt }} <br>
                        Straße: {{ $viewData["address"]->Strasse . " "
                        . $viewData["address"]->Nummer }} <br>
                        <br>
                        <p class="mb-sm-auto">Letzter Login: {{ $viewData["user"]->updatedAt }}</p>

                        User-Rolle: {{ $viewData["user"]->role }} <br><br>
                        <p class="card-text">
                            <a class="align-content-lg-center"
                               href="{{ route('user.editor-id', $viewData["user"]->id) }}">
                                <small class="text-muted">Userdaten bearbeiten</small>
                            </a>
                        </p>
                        <br>

                        Fahrzeuge:
                        <table class="table table-bordered">
                            <tr class="table-primary">
                                <th>Kennzeichen</th>
                                <th>Hersteller</th>
                                <th>Model</th>
                                <th>Farbe</th>
                                <th>Vorschau</th>
                                <th>Parkplatz</th>
                                <th>Buchen/Stornieren</th>
                            </tr>
                            <object{{ $i = 0 }}>
                                @foreach($viewData['cars'] as $car)
                                    <tr class="table-active">
                                        <td>{{ $car->sign }}</td>
                                        <td>{{ $car->manufacturer }}</td>
                                        <td>{{ $car->model }}</td>
                                        <td>{{ $car->color }}</td>
                                        <td>
                                            {{-- Fahrzeugbild bekommt einen Link, wenn es noch keinem Parkplatz zugeordnet ist--}}
                                            @if(!isset($viewData['cars'][$i++]->parkingSpot->number))
                                                <a href="{{ route('cars.show', ['id'=> $car->id]) }}">
                                                    <img src="{{ asset('/storage/media/'. $car->image) }}"
                                                         class="img-thumbnail row-cols-sm-4"
                                                         alt="cd">
                                                </a>
                                            @else
                                                <img src="{{ asset('/storage/media/'. $car->image) }}"
                                                     class="img-thumbnail row-cols-sm-4"
                                                     alt="{{asset('/storage/media/testCar.png')}}">
                                            @endif
                                        </td>
                                        <td>{{ $car->parkingSpot->number ?? ''}} </td>
                                        {{-- Parkplatznummer wird angezeigt, wenn das fahrzeug einen Parkplatz reserviert hat, alternativ erscheint ein Button zur Stornierung des Parkplatzes--}}
                                        <td> @if(!isset($car->parkingSpot->number))
                                                <a href="{{ route('cars.show', ['id'=> $car->id]) }}">
                                                    Parkplatz auswählen
                                                </a>
                                            @else
                                                <a href="{{ route('parking_spots.reserve.cancel', [$car->parkingSpot->id]) }}"
                                                   class="btn btn-danger text-white ">
                                                    <p class="pe-lg-4">Reservierung
                                                        Parkplatz&nbsp;{{$car->parkingSpot->number}}&nbsp;löschen</p>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                            @endforeach
                        </table>
                    </h5>
                </div>
            </div>
        </div>
    </div>
@endsection

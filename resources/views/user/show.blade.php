@php use App\Models\Car;use App\Models\ParkingSpot;use App\Models\User; @endphp
@extends('welcome')
@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])
@section('content')
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="{{ asset('/storage/media/'. $viewData['user']->getImage()) }}"
                     class="img-card rounded-start"
                     alt="Image not found">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">
                        Name: {{ $viewData["user"]->getName() }} <br>
{{--                        {{dd($viewData)}}--}}
                        eMail: {{ $viewData["user"]->getEmail() }} <br>
                        Telefon: {{ $viewData["user"]->getTelefon() }} <br>
                        User-Rolle: {{ $viewData["user"]->getRole() }} <br><br>
                        Fahrzeuge:
                        <table class="table table-bordered">
                            <tr class="table-primary">
                                <th>Kennzeichen</th>
                                <th>Hersteller</th>
                                <th>Model</th>
                                <th>Farbe</th>
                                <th>Vorschau</th>
                                <th>Parkplatz</th>
                            </tr>
                            <object{{ $i = 0 }}>
                            @foreach($viewData['car'] as $car)
                                <tr class="table-active">
                                    <td>{{ $car->sign }}</td>
                                    <td>{{ $car->manufacturer }}</td>
                                    <td>{{ $car->model }}</td>
                                    <td>{{ $car->color }}</td>
                                    <td>{{--//TODO angepasste View nach fahrzueg reserviert selbst Parkplatz--}}
                                        <a href="{{ route('cars.show', ['id'=> $car->getId()]) }}">
                                            <img src="{{ asset('/storage/media/'. $car->image) }}"
                                                 class="img-thumbnail row-cols-sm-4" alt="image not found">
                                        </a>
                                    </td>
                                    {{ dd($viewData['car'][0]->parkingSpot, $viewData['car'][2]->parkingSpot) }}
                                    <td>{{ $viewData['cars'][$i++]->parkingSpot->number ?? 'button'}} </td>

                                </tr>
                            @endforeach
                        </table>
                    </h5>
                    <p class="card-text">Letzter Login: {{ $viewData["user"]->getupdatedAt() }}</p>
                    <p class="card-text">
                        <a class="link-light"
                           href="{{ route('user.editor-id', $viewData["user"]->getId()) }}">
                            <small class="text-muted">Userdaten bearbeiten (coming soon)</small>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

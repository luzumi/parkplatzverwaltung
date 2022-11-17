@php use App\Models\Car;use App\Models\ParkingSpot;use App\Models\User; @endphp
@extends('welcome')
@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])
@section('content')
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="{{ asset('/storage/media/'. User::findOrFail($viewData['user'])->getImage()) }}"
                     class="img-card rounded-start"
                     alt="Image not found">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">
                        Name: {{ User::findOrFail($viewData["user"])->getName() }} <br>
                        eMail: {{ User::findOrFail($viewData["user"])->getEmail() }} <br>
                        Telefon: {{ User::findOrFail($viewData["user"])->getTelefon() }} <br>
                        User-Rolle: {{ User::findOrFail($viewData["user"])->getRole() }} <br><br>
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
                                @foreach(Car::all()->where('user_id', $viewData['user']) as $car))
                                <tr class="table-active">
                                    <td>{{ $car->sign }}</td>
                                    <td>{{ $car->manufacturer }}</td>
                                    <td>{{ $car->model }}</td>
                                    <td>{{ $car->color }}</td>
                                    <td>
                                        <a href="{{ route('cars.show', ['id'=> $car->getId()]) }}">
                                            <img src="{{ asset('/storage/media/'. $car->image) }}"
                                                 class="img-thumbnail row-cols-sm-4" alt="image not found">
                                        </a>
                                    </td>
                                                                {{--//TODO PASCHT NET--}}
                                    <td>{{ ParkingSpot::findOrFail($viewData['user'])[$i]->number ?? 'button'}} </td>
                                </tr>
                            @endforeach
                        </table>
                    </h5>
                    <p class="card-text">Letzter Login: {{ User::findOrFail($viewData["user"])->getupdatedAt() }}</p>
                    <p class="card-text">
                        <a class="link-light"
                           href="{{ route('user.editor-id', User::findOrFail($viewData["user"])->getId()) }}">
                            <small class="text-muted">Userdaten bearbeiten (coming soon)</small>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

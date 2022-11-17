@php use function PHPUnit\Framework\isEmpty; @endphp
@extends('welcome')
@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])
@section('content')
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4">
{{--                                {{dd($viewData, 1231123)}}--}}
                <img src="{{ asset('/storage/media/'. $viewData['user']->getImage()) }}" class="img-card rounded-start"
                     alt="Image not found">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">
                        Name: {{ $viewData["user"]->getName() }} <br>
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
                                @foreach($viewData['user']->cars as $car)
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
                                        <td>{{ $viewData['user']->parkingSpot[$i++]->number ??  'button'}} </td>
                                    </tr>
                            @endforeach
                            {{--                            <p>{{ $viewData['cars'][0]->user->name }} </p>--}}
                        </table>
                    </h5>
                    <p class="card-text">Letzter Login: {{ $viewData["user"]->getupdatedAt() }}</p>
                    <p class="card-text">

                        <a href="{{ route('cars.show', ['id'=> $car->getId()]) }}">cars</a>
                        {{--                        <a class="link-light" href="{{ route('user.editor-id', $viewData["user"]->getId()) }}">--}}
                        <small class="text-muted">Userdaten bearbeiten (coming soon)</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

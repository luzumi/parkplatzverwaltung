@extends('layouts.admin')
@section('title', $viewData["title"])
@section('content')
    <div class="card h-50">
        <div class="card mb-4">
            <div class="card-header">
                Neues Fahrzeug erstellen
            </div>
            <div class="card-body">
                @if($errors->any())
                    <ul class="alert alert-danger list-unstyled">
                        @foreach($errors->all() as $error)
                            <li> - {{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
                {{--Usereingaben - Editiere ausgewähltes fahrzeug--}}
                <form method="POST" action="{{ route('admin.car.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="mb-1 row">
                                <div class="col-lg-10 col-md-6 col-sm-12">
                                    <label>
                                        <input name="sign" value="{{ old('sign') }}" type="text" class="form-control">
                                    </label>
                                    <label class="col-lg-10 col-sm-12 col-form-label">Kennzeichen</label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3 row">
                                <div class="col-lg-10 col-md-6 col-sm-12">
                                    <label>
                                        <input name="manufacturer" value="{{ old('manufacturer') }}" type="text"
                                               class="form-control">
                                    </label>
                                    <label class="col-lg-10 col-md-6 col-sm-12 col-form-label">Hersteller</label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3 row">
                                <div class="col-lg-10 col-md-6 col-sm-12">
                                    <label>
                                        <input name="model" value="{{ old('model') }}" type="text" class="form-control">
                                    </label>
                                    <label class="col-lg-10 col-md-6 col-sm-12 col-form-label">Model</label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3 row">
                                <div class="col-lg-10 col-md-6 col-sm-12">
                                    <label>
                                        <input name="color" value="{{ old('color') }}" type="text" class="form-control">
                                    </label>
                                    <label class="col-lg-10 col-md-6 col-sm-12 col-form-label">Farbe</label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3 row">
                                <div class="col-lg-10 col-md-6 col-sm-12">
                                    <input name="image" value="{{ old('image') }}" type="file" class="form-control-sm">
                                    <label class="col-lg-10 col-md-6 col-sm-12 col-form-label">Bild</label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            &nbsp;
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Absenden</button>
                </form>
            </div>
        </div>
        {{-- Tabelle aller Fahrzeuge--}}
        <div class="card">
            <div class="card-header ">
                Manage Cars
            </div>
            <div class="card-body  overflow-auto">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th class="col">User</th>
                        <th class="col">Parkplatz</th>
                        <th class="col">Vorschau</th>
                        <th class="col">Kennzeichen</th>
                        <th class="col">Hersteller</th>
                        <th class="col">Modell</th>
                        <th class="col">Farbe</th>
                        <th class="col">Edit</th>
                        <th class="col">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($viewData["cars"] as $car)
                        <tr class="img-thumbnail img-card">
                            {{-- Fahrzeug hat Parkplatz reserviert? wenn ja, Anzeige der Parkplatznummer --}}
                            <td>{{ $car->user_id }}</td>
                            @if(!isset($car->parkingSpot->number))
                                <td>{{ '' }}</td>
                            @else
                                <td>{{ $car->parkingSpot->number }}</td>
                            @endif
                            <td class="img-profile"><img src="{{ asset('/storage/media/'. $car->image) }}"
                                                         class="img-fluid rounded-start" alt="Image not found"></td>
                            <td>{{ $car->sign }}</td>
                            <td>{{ $car->manufacturer }}</td>
                            <td>{{ $car->model }}</td>
                            <td>{{ $car->color }}</td>
                            {{--Buttons für Edit und Delete--}}
                            <td>
                                <a class="btn btn-primary" href="{{ route('admin.car.edit', ['id'=>$car->id]) }}">
                                    <i class="bi-pencil"> </i>
                                </a>
                            </td>
                            <td>
                                <form action="{{ route('admin.car.delete', $car->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger">
                                        <i class="bi-trash"> </i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

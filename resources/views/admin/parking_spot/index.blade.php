@extends('layouts.admin')
@section('title', $viewData["title"])
@section('content')

    <div class="card mb-4">
        <div class="card-header">
            Neuen Parkplatz erstellen
        </div>
        <div class="card-body">
            @if($errors->any())
                <ul class="alert alert-danger list-unstyled">
                    @foreach($errors->all() as $error)
                        <li> - {{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('admin.parking_spot.store') }}">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="mb-1 row">
                            <div class="col-lg-10 col-md-6 col-sm-12">
                                <label>
                                    <select name="status">
                                        <option value="frei">frei</option>
                                        <option value="electro">electro</option>
                                        <option value="reserviert">reserviert</option>
                                        <option value="Behindertenparkplatz">Behindertenparkplatz</option>
                                        <option value="besetzt">besetzt</option>
                                        <option value="gesperrt">gesperrt</option>
                                    </select>
                                </label>
                                <label class="col-lg-10 col-sm-12 col-form-label">Status</label>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Absenden</button>
            </form>
        </div>
    </div>

{{-- Anzeige der vorhandenen Parkplätze--}}
    <div class="card">
        <div class="card-header">
            Manage Parking-Spots
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nummer</th>
                    <th scope="col">Reihe</th>
                    <th scope="col">Status</th>
                    <th scope="col">Fahrzeug</th>
                    <th scope="col">Vorschau</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($viewData["parking_spots"] as $parking_spot)
                    <tr>
                        <td>{{ $parking_spot->id }}</td>
                        <td>{{ $parking_spot->number }}</td>
                        <td>{{ $parking_spot->row }}</td>
                        <td>{{ $parking_spot->status }}</td>
{{-- Ausgabe des Nummernschildes --}}
                        <td>{{ $parking_spot->car->sign ?? '' }}</td>
{{-- Ausgabe des Vorschaubildes --}}
                        <td>
                            <img src="{{ asset('/storage/media/'. $parking_spot->image) }}"
                                 class="img-profile" alt=" ">
                        </td>
{{-- Buttons edit und delete--}}
                        <td>
                            <a class="btn btn-primary"
                               href="{{ route('admin.parking-spot.edit', ['id'=>$parking_spot->id]) }}">
                                <i class="bi-pencil"> </i>
                            </a>
                        </td>
                        <td>
                            <form action="{{ route('admin.parking_spot.delete', $parking_spot->id) }}"
                                  method="POST">
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
@endsection

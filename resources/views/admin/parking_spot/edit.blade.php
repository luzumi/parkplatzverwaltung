@extends('layouts.admin')
@section('title', $viewData["title"])
@section('content')
    <div class="card mb-4">
        <div class="card-header">
            Editiere Parkplatz {{$viewData['parking_spot']->id}}
        </div>
        <div class="card-body">
            @if($errors->any())
                <ul class="alert alert-danger list-unstyled">
                    @foreach($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('admin.parking-spot.update', ['id'=> $viewData['parking_spot']->id]) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col">
                        <div class="mb-3 row">
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
                                <label class="col-lg-10 col-md-6 col-sm-12 col-form-label">Status:</label>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Edit</button>
            </form>
        </div>
    </div>
@endsection

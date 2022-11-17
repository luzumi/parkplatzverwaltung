@extends('welcome')
@section('title', $viewData["title"])
@section('content')

    <div class="card mb-4">
        <div class="card-header">
            Neues Fahrzeug erstellen {{ $viewData['name']}}
        </div>
        <div class="card-body">
            @if($errors->any())
                <ul class="alert alert-danger list-unstyled">
                    @foreach($errors->all() as $error)
                        <li> - {{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('user.addCar.storeCar') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="mb-1 row">
                            <div class="col-lg-10 col-md-6 col-sm-12">
                                <input name="sign" value="{{ old('sign') }}" type="text" class="form-control">
                                <label class="col-lg-10 col-sm-12 col-form-label">Kennzeichen</label>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3 row">
                            <div class="col-lg-10 col-md-6 col-sm-12">
                                <input name="manufacturer" value="{{ old('manufacturer') }}" type="text" class="form-control">
                                <label class="col-lg-10 col-md-6 col-sm-12 col-form-label">Hersteller</label>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3 row">
                            <div class="col-lg-10 col-md-6 col-sm-12">
                                <input name="model" value="{{ old('model') }}" type="text" class="form-control">
                                <label class="col-lg-10 col-md-6 col-sm-12 col-form-label">Model</label>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3 row">
                            <div class="col-lg-10 col-md-6 col-sm-12">
                                <input name="color" value="{{ old('color') }}" type="text" class="form-control">
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
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Absenden</button>
            </form>
        </div>
    </div>
@endsection
